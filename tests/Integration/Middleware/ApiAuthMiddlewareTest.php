<?php

declare(strict_types=1);

namespace Tests\Integration\Middleware;

use Blog\Middleware\ApiAuthMiddleware;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ApiAuthMiddlewareTest extends TestCase
{
    private Psr17Factory $factory;
    private ApiAuthMiddleware $middleware;

    protected function setUp(): void
    {
        $this->factory = new Psr17Factory();
        $this->middleware = new ApiAuthMiddleware();
    }

    public function test_allows_public_api_routes_without_authentication(): void
    {
        $request = $this->createServerRequest('GET', '/api/articles');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_blocks_unauthenticated_user_from_protected_api_routes(): void
    {
        // Clear any existing session
        $_SESSION = [];

        $request = $this->createServerRequest('POST', '/api/articles');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(401, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);
        $this->assertSame('Authentication required', $body['error']);
    }

    public function test_blocks_unauthenticated_user_from_put_api_routes(): void
    {
        $_SESSION = [];

        $request = $this->createServerRequest('PUT', '/api/articles/123');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(401, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);
        $this->assertSame('Authentication required', $body['error']);
    }

    public function test_blocks_unauthenticated_user_from_delete_api_routes(): void
    {
        $_SESSION = [];

        $request = $this->createServerRequest('DELETE', '/api/articles/123');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(401, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);
        $this->assertSame('Authentication required', $body['error']);
    }

    public function test_allows_authenticated_user_to_access_protected_api_routes(): void
    {
        // Set up authenticated session
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';

        $request = $this->createServerRequest('POST', '/api/articles');
        $handler = $this->createRequestHandler(new Response(201));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(201, $response->getStatusCode());
    }

    public function test_allows_authenticated_user_to_access_put_api_routes(): void
    {
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';

        $request = $this->createServerRequest('PUT', '/api/articles/123');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_allows_authenticated_user_to_access_delete_api_routes(): void
    {
        $_SESSION['user_id'] = 'test-user-id';
        $_SESSION['user_role'] = 'ROLE_USER';

        $request = $this->createServerRequest('DELETE', '/api/articles/123');
        $handler = $this->createRequestHandler(new Response(204));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(204, $response->getStatusCode());
    }

    public function test_allows_get_requests_to_api_articles_without_authentication(): void
    {
        $_SESSION = [];

        $request = $this->createServerRequest('GET', '/api/articles');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_allows_get_requests_to_specific_article_without_authentication(): void
    {
        $_SESSION = [];

        $request = $this->createServerRequest('GET', '/api/articles/123');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_allows_get_requests_to_article_search_without_authentication(): void
    {
        $_SESSION = [];

        $request = $this->createServerRequest('GET', '/api/articles/search?q=test');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_blocks_post_requests_to_api_articles_without_authentication(): void
    {
        $_SESSION = [];

        $request = $this->createServerRequest('POST', '/api/articles');
        $handler = $this->createRequestHandler(new Response(201));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(401, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);
        $this->assertSame('Authentication required', $body['error']);
    }

    public function test_blocks_patch_requests_to_api_articles_without_authentication(): void
    {
        $_SESSION = [];

        $request = $this->createServerRequest('PATCH', '/api/articles/123');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(401, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);
        $this->assertSame('Authentication required', $body['error']);
    }

    public function test_returns_json_content_type_for_authentication_errors(): void
    {
        $_SESSION = [];

        $request = $this->createServerRequest('POST', '/api/articles');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
    }

    public function test_allows_non_api_routes_without_interference(): void
    {
        $_SESSION = [];

        $request = $this->createServerRequest('GET', '/blog');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_allows_mark_routes_without_interference(): void
    {
        $_SESSION = [];

        $request = $this->createServerRequest('GET', '/mark/dashboard');
        $handler = $this->createRequestHandler(new Response(200));

        $response = $this->middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_handles_case_sensitive_api_routes(): void
    {
        $_SESSION = [];

        $request = $this->createServerRequest('POST', '/API/articles');
        $handler = $this->createRequestHandler(new Response(201));

        $response = $this->middleware->process($request, $handler);

        // Should not block non-matching case
        $this->assertSame(201, $response->getStatusCode());
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
