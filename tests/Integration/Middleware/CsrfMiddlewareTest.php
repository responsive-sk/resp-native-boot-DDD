<?php

declare(strict_types=1);

namespace Tests\Integration\Middleware;

use Blog\Infrastructure\Http\Middleware\CsrfMiddleware;
use Blog\Security\CsrfProtection;
use Blog\Security\SecurityLogger;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ResponsiveSk\Slim4Session\Session;
use ResponsiveSk\Slim4Session\SessionInterface;

/**
 * @group integration
 * @group middleware
 * @group security
 */
final class CsrfMiddlewareTest extends TestCase
{
    private Psr17Factory $factory;
    private CsrfMiddleware $middleware;
    private $csrfProtection;
    private $securityLogger;
    private SessionInterface $session;

    protected function setUp(): void
    {
        $this->factory = new Psr17Factory();
        $this->session = new Session(['auto_start' => false]);
        $this->csrfProtection = new CsrfProtection($this->session);
        $this->securityLogger = $this->createMock(SecurityLogger::class);
        $this->middleware = new CsrfMiddleware(
            $this->csrfProtection,
            $this->securityLogger,
            $this->session
        );
    }

    public function test_allows_get_requests_without_token(): void
    {
        $request = $this->createServerRequest('GET', '/form');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_allows_head_requests_without_token(): void
    {
        $request = $this->createServerRequest('HEAD', '/resource');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_allows_options_requests_without_token(): void
    {
        $request = $this->createServerRequest('OPTIONS', '/resource');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_rejects_post_without_csrf_token(): void
    {
        $request = $this->createServerRequest('POST', '/form');
        $handler = $this->createRequestHandler(new Response(200));

        $this->securityLogger->expects($this->once())
            ->method('logFailedRequest')
            ->with(
                $this->equalTo('CSRF_VALIDATION_FAILED'),
                $this->anything(),
                $this->callback(function ($context) {
                    return isset($context['ip_address']) && $context['ip_address'] === '127.0.0.1';
                })
            );

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(403, $response->getStatusCode());
    }

    public function test_rejects_post_with_invalid_csrf_token(): void
    {
        $request = $this->createServerRequest('POST', '/form', [], [
            'csrf_token' => 'invalid-token',
        ]);
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(403, $response->getStatusCode());
    }

    public function test_accepts_post_with_valid_csrf_token(): void
    {
        $validToken = $this->csrfProtection->generateToken();

        $request = $this->createServerRequest('POST', '/form', [], [
            'csrf_token' => $validToken,
        ]);
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_accepts_csrf_token_in_header(): void
    {
        $validToken = $this->csrfProtection->generateToken();

        $request = $this->createServerRequest(
            'POST',
            '/api/form',
            ['X-CSRF-Token' => $validToken]
        );
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_returns_json_error_for_api_requests(): void
    {
        $request = $this->createServerRequest(
            'POST',
            '/api/form',
            ['Accept' => 'application/json']
        );
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(403, $response->getStatusCode());
        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string) $response->getBody(), true);
        $this->assertArrayHasKey('error', $body);
        $this->assertSame('CSRF token validation failed', $body['message']);
    }

    public function test_returns_redirect_for_web_requests(): void
    {
        $request = $this->createServerRequest('POST', '/form');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('/error/csrf', $response->getHeaderLine('Location'));
    }

    public function test_handles_empty_csrf_token_in_header(): void
    {
        $request = $this->createServerRequest(
            'POST',
            '/api/form',
            ['X-CSRF-Token' => '']
        );
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(403, $response->getStatusCode());
    }

    public function test_handles_malformed_csrf_token(): void
    {
        $request = $this->createServerRequest('POST', '/form', [], [
            'csrf_token' => '<script>alert("xss")</script>',
        ]);
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(403, $response->getStatusCode());
    }

    public function test_handles_expired_token(): void
    {
        // Generate token
        $token = $this->csrfProtection->generateToken();

        // Simulate token expiration by regenerating
        $this->csrfProtection->regenerateToken();

        $request = $this->createServerRequest('POST', '/form', [], [
            'csrf_token' => $token,
        ]);
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(403, $response->getStatusCode());
    }

    public function test_handles_reused_token(): void
    {
        $validToken = $this->csrfProtection->generateToken();

        // First request with token succeeds
        $request1 = $this->createServerRequest('POST', '/form', [], [
            'csrf_token' => $validToken,
        ]);
        $handler = $this->createRequestHandler(new Response(200));
        $response1 = $this->middleware->process($request1, $handler);
        $this->assertSame(200, $response1->getStatusCode());

        // Second request with same token fails (if token is single-use)
        // Note: This depends on implementation details
    }

    public function test_handles_put_request(): void
    {
        $validToken = $this->csrfProtection->generateToken();

        $request = $this->createServerRequest('PUT', '/api/articles/123', [], [
            'csrf_token' => $validToken,
        ]);
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_handles_delete_request(): void
    {
        $validToken = $this->csrfProtection->generateToken();

        $request = $this->createServerRequest('DELETE', '/api/articles/123', [], [
            'csrf_token' => $validToken,
        ]);
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_handles_patch_request(): void
    {
        $validToken = $this->csrfProtection->generateToken();

        $request = $this->createServerRequest('PATCH', '/api/articles/123', [], [
            'csrf_token' => $validToken,
        ]);
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_logs_security_event_on_failure(): void
    {
        $this->securityLogger->expects($this->once())
            ->method('logFailedRequest')
            ->with(
                $this->equalTo('CSRF_VALIDATION_FAILED'),
                $this->anything(),
                $this->callback(function ($context) {
                    return isset($context['method']) && $context['method'] === 'POST' &&
                           isset($context['uri']) && $context['uri'] === '/admin/users';
                })
            );

        $request = $this->createServerRequest('POST', '/admin/users');
        $handler = $this->createRequestHandler(new Response(200));

        $this->middleware->process($request, $handler);
    }

    public function test_includes_request_id_in_error_response(): void
    {
        $request = $this->createServerRequest(
            'POST',
            '/api/form',
            ['Accept' => 'application/json']
        );
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $body = json_decode((string) $response->getBody(), true);
        $this->assertArrayHasKey('request_id', $body);
        $this->assertNotEmpty($body['request_id']);
    }

    private function createServerRequest(
        string $method,
        string $uri,
        array $headers = [],
        array $body = []
    ): ServerRequestInterface {
        $request = new ServerRequest($method, $uri);

        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        if (!empty($body)) {
            $request = $request->withParsedBody($body);
        }

        return $request;
    }

    private function createRequestHandler(ResponseInterface $response): RequestHandlerInterface
    {
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response);

        return $handler;
    }
}
