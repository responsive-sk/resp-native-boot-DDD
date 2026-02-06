<?php

declare(strict_types=1);

namespace Tests\Integration\Middleware;

use Blog\Middleware\AuthMiddleware;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthMiddlewareTest extends TestCase
{
    private Psr17Factory $factory;
    private AuthMiddleware $middleware;

    protected function setUp(): void
    {
        $this->factory = new Psr17Factory();
        $this->middleware = new AuthMiddleware();
    }

    public function test_allows_public_routes_without_authentication(): void
    {
        $request = $this->createServerRequest('GET', '/blog');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_allows_home_page_without_authentication(): void
    {
        $request = $this->createServerRequest('GET', '/');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_redirects_unauthenticated_user_from_protected_article_routes(): void
    {
        // Clear any existing session
        $_SESSION = [];

        $request = $this->createServerRequest('GET', '/article/create');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('/login', $response->getHeaderLine('Location'));
    }

    public function test_redirects_unauthenticated_user_from_protected_mark_routes(): void
    {
        // Clear any existing session
        $_SESSION = [];

        $request = $this->createServerRequest('GET', '/mark/dashboard');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('/login', $response->getHeaderLine('Location'));
    }

    public function test_allows_authenticated_user_to_access_protected_routes(): void
    {
        // Set up authenticated session
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';

        $request = $this->createServerRequest('GET', '/article/create');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_redirects_user_without_mark_role_from_mark_routes(): void
    {
        // Set up authenticated user without MARK role
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';

        $request = $this->createServerRequest('GET', '/mark/dashboard');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('/blog', $response->getHeaderLine('Location'));
    }

    public function test_allows_mark_user_to_access_mark_routes(): void
    {
        // Set up authenticated user with MARK role
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_MARK';

        $request = $this->createServerRequest('GET', '/mark/dashboard');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_adds_user_attribute_to_request(): void
    {
        // Set up authenticated session
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';

        $request = $this->createServerRequest('GET', '/article/create');
        $handler = $this->createRequestHandler(new Response(200));

        $this->middleware->process($request, $handler);

        // Check that user attribute was added to request
        $user = $request->getAttribute('user');
        $this->assertIsArray($user);
        $this->assertSame('test-user-id', $user['id']);
        $this->assertSame('ROLE_USER', $user['role']);
    }

    public function test_adds_null_user_attribute_for_unauthenticated_user(): void
    {
        // Clear session
        $_SESSION = [];

        $request = $this->createServerRequest('GET', '/blog');
        $handler = $this->createRequestHandler(new Response(200));

        $this->middleware->process($request, $handler);

        // Check that user attribute was set to null
        $user = $request->getAttribute('user');
        $this->assertNull($user);
    }

    public function test_handles_nested_article_routes(): void
    {
        $_SESSION = [];

        $request = $this->createServerRequest('GET', '/article/123/edit');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('/login', $response->getHeaderLine('Location'));
    }

    public function test_handles_nested_mark_routes(): void
    {
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';

        $request = $this->createServerRequest('GET', '/mark/articles/create');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('/blog', $response->getHeaderLine('Location'));
    }

    public function test_allows_api_routes_without_authentication(): void
    {
        // API routes should not be handled by AuthMiddleware
        $request = $this->createServerRequest('GET', '/api/articles');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_allows_login_page_without_authentication(): void
    {
        $request = $this->createServerRequest('GET', '/login');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_allows_register_page_without_authentication(): void
    {
        $request = $this->createServerRequest('GET', '/register');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
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
        // Clean up session after each test
        $_SESSION = [];
    }
}
