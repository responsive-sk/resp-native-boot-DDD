<?php

declare(strict_types=1);

namespace Tests\Integration\Middleware;

use Blog\Infrastructure\Http\Middleware\ErrorHandlerMiddleware;
use Blog\Security\SecurityLogger;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * @group integration
 * @group middleware
 */
final class ErrorHandlerMiddlewareTest extends TestCase
{
    private Psr17Factory $factory;
    private ErrorHandlerMiddleware $middleware;
    private $logger;
    private $securityLogger;

    protected function setUp(): void
    {
        $this->factory = new Psr17Factory();
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->securityLogger = $this->createMock(SecurityLogger::class);
        $this->middleware = new ErrorHandlerMiddleware(
            $this->logger,
            $this->securityLogger,
            'production'
        );
    }

    public function test_handles_exception_in_handler(): void
    {
        $request = $this->createServerRequest('GET', '/test');
        $handler = $this->createFailingHandler(new \RuntimeException('Something went wrong'));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(500, $response->getStatusCode());
    }

    public function test_returns_json_for_api_requests(): void
    {
        $request = $this->createServerRequest('GET', '/api/test', ['Accept' => 'application/json']);
        $handler = $this->createFailingHandler(new \RuntimeException('API Error'));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string) $response->getBody(), true);
        $this->assertArrayHasKey('error', $body);
        $this->assertArrayHasKey('message', $body);
    }

    public function test_returns_html_for_web_requests(): void
    {
        $request = $this->createServerRequest('GET', '/test');
        $handler = $this->createFailingHandler(new \RuntimeException('Web Error'));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(500, $response->getStatusCode());
        $this->assertStringContainsString('text/html', $response->getHeaderLine('Content-Type'));
    }

    public function test_handles_not_found_exception(): void
    {
        $request = $this->createServerRequest('GET', '/nonexistent');
        $handler = $this->createFailingHandler(
            new \RuntimeException('Not Found', 404)
        );

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function test_handles_validation_exception(): void
    {
        $request = $this->createServerRequest('POST', '/api/articles', ['Accept' => 'application/json']);
        $handler = $this->createFailingHandler(
            new \InvalidArgumentException('Validation failed: Title is required')
        );

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(400, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);
        $this->assertStringContainsString('Validation failed', $body['message']);
    }

    public function test_handles_unauthorized_exception(): void
    {
        $request = $this->createServerRequest('GET', '/api/admin', ['Accept' => 'application/json']);
        $handler = $this->createFailingHandler(
            new \RuntimeException('Unauthorized', 401)
        );

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(401, $response->getStatusCode());
    }

    public function test_handles_forbidden_exception(): void
    {
        $request = $this->createServerRequest('GET', '/api/admin', ['Accept' => 'application/json']);
        $handler = $this->createFailingHandler(
            new \RuntimeException('Forbidden', 403)
        );

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(403, $response->getStatusCode());
    }

    public function test_logs_errors_in_production(): void
    {
        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                $this->stringContains('Uncaught exception'),
                $this->arrayHasKey('exception')
            );

        $request = $this->createServerRequest('GET', '/test');
        $handler = $this->createFailingHandler(new \RuntimeException('Production Error'));

        $this->middleware->process($request, $handler);
    }

    public function test_hides_details_in_production(): void
    {
        $request = $this->createServerRequest('GET', '/api/test', ['Accept' => 'application/json']);
        $handler = $this->createFailingHandler(new \RuntimeException('Sensitive Error Details'));

        $response = $this->middleware->process($request, $handler);

        $body = json_decode((string) $response->getBody(), true);
        $this->assertStringNotContainsString('Sensitive Error Details', $body['message']);
        $this->assertSame('An error occurred', $body['message']);
    }

    public function test_shows_details_in_development(): void
    {
        $devMiddleware = new ErrorHandlerMiddleware(
            $this->logger,
            $this->securityLogger,
            'development'
        );

        $request = $this->createServerRequest('GET', '/api/test', ['Accept' => 'application/json']);
        $handler = $this->createFailingHandler(new \RuntimeException('Detailed Error'));

        $response = $devMiddleware->process($request, $handler);

        $body = json_decode((string) $response->getBody(), true);
        $this->assertStringContainsString('Detailed Error', $body['message']);
    }

    public function test_handles_database_connection_error(): void
    {
        $request = $this->createServerRequest('GET', '/api/articles', ['Accept' => 'application/json']);
        $handler = $this->createFailingHandler(
            new \PDOException('Connection refused')
        );

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(500, $response->getStatusCode());
        $this->assertStringNotContainsString('Connection refused', (string) $response->getBody());
    }

    public function test_handles_security_exception(): void
    {
        $this->securityLogger->expects($this->once())
            ->method('log')
            ->with(
                $this->equalTo('SECURITY_VIOLATION'),
                $this->anything(),
                $this->anything()
            );

        $request = $this->createServerRequest('POST', '/api/login', ['Accept' => 'application/json']);
        $handler = $this->createFailingHandler(
            new \RuntimeException('CSRF token validation failed', 403)
        );

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(403, $response->getStatusCode());
    }

    public function test_handles_rate_limit_exceeded(): void
    {
        $request = $this->createServerRequest('POST', '/api/login', ['Accept' => 'application/json']);
        $handler = $this->createFailingHandler(
            new \RuntimeException('Rate limit exceeded', 429)
        );

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(429, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Retry-After'));
    }

    public function test_handles_method_not_allowed(): void
    {
        $request = $this->createServerRequest('DELETE', '/api/articles', ['Accept' => 'application/json']);
        $handler = $this->createFailingHandler(
            new \RuntimeException('Method not allowed', 405)
        );

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(405, $response->getStatusCode());
    }

    public function test_handles_conflict_exception(): void
    {
        $request = $this->createServerRequest('POST', '/api/users', ['Accept' => 'application/json']);
        $handler = $this->createFailingHandler(
            new \RuntimeException('User with this email already exists', 409)
        );

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(409, $response->getStatusCode());
    }

    public function test_handles_file_upload_error(): void
    {
        $request = $this->createServerRequest('POST', '/api/upload', ['Accept' => 'application/json']);
        $handler = $this->createFailingHandler(
            new \RuntimeException('File too large', 413)
        );

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(413, $response->getStatusCode());
    }

    public function test_handles_service_unavailable(): void
    {
        $request = $this->createServerRequest('GET', '/api/external', ['Accept' => 'application/json']);
        $handler = $this->createFailingHandler(
            new \RuntimeException('External service unavailable', 503)
        );

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(503, $response->getStatusCode());
    }

    private function createServerRequest(string $method, string $uri, array $headers = []): ServerRequestInterface
    {
        $request = new ServerRequest($method, $uri);

        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        return $request;
    }

    private function createFailingHandler(\Throwable $exception): RequestHandlerInterface
    {
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willThrowException($exception);

        return $handler;
    }
}
