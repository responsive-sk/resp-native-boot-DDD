<?php

declare(strict_types=1);

namespace Blog\Middleware;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class CorsMiddleware implements MiddlewareInterface
{
    public function __construct(
        private array $allowedOrigins = [
            'http://localhost:5173',
            'http://localhost:3000',
            'http://127.0.0.1:5173',
            'http://127.0.0.1:3000',
        ],
        private array $allowedMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
        private array $allowedHeaders = ['Content-Type', 'Authorization', 'X-Requested-With']
    ) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // Handle preflight OPTIONS request
        if ($request->getMethod() === 'OPTIONS') {
            return $this->addCorsHeaders($request, new Response(204));
        }

        // Process request and add CORS headers to response
        $response = $handler->handle($request);

        return $this->addCorsHeaders($request, $response);
    }

    private function addCorsHeaders(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        // Get the Origin header from request
        $origin = $request->getHeaderLine('Origin');

        // Check if origin is allowed
        if (!in_array($origin, $this->allowedOrigins, true)) {
            // If origin not allowed, don't add CORS headers
            return $response;
        }

        // CRITICAL FIX: Access-Control-Allow-Origin must be exact origin, not comma-separated list
        return $response
            ->withHeader('Access-Control-Allow-Origin', $origin)  // ← Single origin, not comma-separated
            ->withHeader('Access-Control-Allow-Methods', implode(', ', $this->allowedMethods))
            ->withHeader('Access-Control-Allow-Headers', implode(', ', $this->allowedHeaders))
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Max-Age', '86400');
    }
}
