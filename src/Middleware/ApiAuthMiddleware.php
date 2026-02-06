<?php

declare(strict_types=1);

namespace Blog\Middleware;

use Blog\Security\Authorization;
use Blog\Security\Exception\AuthenticationException;
use Blog\Security\Exception\AuthorizationException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ApiAuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly \Blog\Security\AuthorizationService $authorization,
        private readonly \Blog\Application\Audit\AuditLogger $auditLogger
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $path = $request->getUri()->getPath();

        // Add user to request attributes (can be null if not authenticated)
        $request = $request->withAttribute('user', $this->authorization->getUser());

        // API endpoints that require authentication
        $protectedApiPrefixes = [
            '/api/articles',  // All article CRUD operations
        ];

        // Check if this is a protected API endpoint
        $isProtectedApi = false;
        foreach ($protectedApiPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $isProtectedApi = true;
                break;
            }
        }

        // If not a protected API endpoint, continue
        if (!$isProtectedApi) {
            return $handler->handle($request);
        }

        // For protected API endpoints - check authentication
        try {
            $this->authorization->requireAuth();
        } catch (AuthenticationException $e) {
            // Log security event
            $this->auditLogger->logAuthorization(
                'api_auth_required',
                null,
                $path,
                false,
                $request,
                ['error' => $e->getMessage()]
            );

            // Return JSON error for API requests
            return new Response(401, ['Content-Type' => 'application/json'], json_encode([
                'success' => false,
                'error' => 'Authentication required',
                'message' => 'You must be logged in to access this resource'
            ]));
        }

        return $handler->handle($request);
    }
}
