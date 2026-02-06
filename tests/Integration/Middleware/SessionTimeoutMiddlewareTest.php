<?php

declare(strict_types=1);

namespace Tests\Integration\Middleware;

use Blog\Infrastructure\Http\Middleware\SessionTimeoutMiddleware;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SessionTimeoutMiddlewareTest extends TestCase
{
    private Psr17Factory $factory;
    private SessionTimeoutMiddleware $middleware;

    protected function setUp(): void
    {
        $this->factory = new Psr17Factory();
        $this->middleware = new SessionTimeoutMiddleware();
    }

    public function test_allows_request_without_session(): void
    {
        // Clear session
        $_SESSION = [];

        $request = $this->createServerRequest('GET', '/blog');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_allows_request_with_valid_session(): void
    {
        // Set up valid session
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';
        $_SESSION['last_activity'] = time();

        $request = $this->createServerRequest('GET', '/blog');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_redirects_expired_session(): void
    {
        // Set up expired session (more than 30 minutes ago for default timeout)
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';
        $_SESSION['last_activity'] = time() - 1801; // 30 minutes + 1 second ago

        $request = $this->createServerRequest('GET', '/blog');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('/login', $response->getHeaderLine('Location'));
        
        // Check that flash message was set
        $this->assertSame('Session expired', $_SESSION['flash_error']);
    }

    public function test_allows_mark_session_with_longer_timeout(): void
    {
        // Set up mark session (2 hour timeout)
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_MARK';
        $_SESSION['last_activity'] = time() - 1800; // 30 minutes ago

        $request = $this->createServerRequest('GET', '/mark/dashboard');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_redirects_expired_mark_session(): void
    {
        // Set up expired mark session (more than 2 hours ago)
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_MARK';
        $_SESSION['last_activity'] = time() - 7201; // 2 hours + 1 second ago

        $request = $this->createServerRequest('GET', '/mark/dashboard');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('/login', $response->getHeaderLine('Location'));
    }

    public function test_allows_api_session_with_longest_timeout(): void
    {
        // Set up API session (24 hour timeout)
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';
        $_SESSION['last_activity'] = time() - 3600; // 1 hour ago

        $request = $this->createServerRequest('GET', '/api/articles');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_redirects_expired_api_session(): void
    {
        // Set up expired API session (more than 24 hours ago)
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';
        $_SESSION['last_activity'] = time() - 86401; // 24 hours + 1 second ago

        $request = $this->createServerRequest('GET', '/api/articles');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('/login', $response->getHeaderLine('Location'));
    }

    public function test_updates_last_activity(): void
    {
        // Set up session with old activity
        $oldActivity = time() - 120; // 2 minutes ago
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';
        $_SESSION['last_activity'] = $oldActivity;

        $request = $this->createServerRequest('GET', '/blog');
        $handler = $this->createRequestHandler(new Response(200));

        $this->middleware->process($request, $handler);

        // Last activity should be updated
        $this->assertGreaterThan($oldActivity, $_SESSION['last_activity']);
    }

    public function test_does_not_update_last_activity_if_recent(): void
    {
        // Set up session with recent activity
        $recentActivity = time() - 30; // 30 seconds ago
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';
        $_SESSION['last_activity'] = $recentActivity;

        $request = $this->createServerRequest('GET', '/blog');
        $handler = $this->createRequestHandler(new Response(200));

        $this->middleware->process($request, $handler);

        // Last activity should not be updated (optimization)
        $this->assertSame($recentActivity, $_SESSION['last_activity']);
    }

    public function test_handles_session_without_last_activity(): void
    {
        // Set up session without last_activity
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';

        $request = $this->createServerRequest('GET', '/blog');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
        // Last activity should be set
        $this->assertArrayHasKey('last_activity', $_SESSION);
    }

    public function test_destroys_session_on_expiry(): void
    {
        // Set up expired session
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';
        $_SESSION['last_activity'] = time() - 1801;
        $_SESSION['other_data'] = 'some_value';

        $request = $this->createServerRequest('GET', '/blog');
        $handler = $this->createRequestHandler(new Response(200));

        $this->middleware->process($request, $handler);

        // Session should be destroyed (only flash_error should remain)
        $this->assertArrayHasKey('flash_error', $_SESSION);
        $this->assertArrayNotHasKey('user_id', $_SESSION);
        $this->assertArrayNotHasKey('user_role', $_SESSION);
        $this->assertArrayNotHasKey('last_activity', $_SESSION);
        $this->assertArrayNotHasKey('other_data', $_SESSION);
    }

    public function test_applies_secure_cookie_headers_on_https(): void
    {
        // Mock HTTPS environment
        $_SERVER['HTTPS'] = 'on';
        
        // Set up valid session
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';
        $_SESSION['last_activity'] = time();

        $request = $this->createServerRequest('GET', '/blog');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        // Should have secure cookie headers
        $this->assertStringContainsString('Secure', $response->getHeaderLine('Set-Cookie'));
    }

    private function createServerRequest(string $method, string $uri): ServerRequestInterface
    {
        return new ServerRequest($method, $uri);
    }

    private function createRequestHandler(ResponseInterface $response): RequestHandlerInterface
    {
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response);
        return $handler;
    }

    protected function tearDown(): void
    {
        // Clean up session and server variables after each test
        $_SESSION = [];
        unset($_SERVER['HTTPS']);
    }
}
