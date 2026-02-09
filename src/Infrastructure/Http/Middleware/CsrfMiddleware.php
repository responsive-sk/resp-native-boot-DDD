<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Middleware;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfMiddleware implements MiddlewareInterface
{
    private const EXEMPT_PATHS = [
        '/api/auth/login',
        '/api/auth/register',
    ];

    public function __construct(
        private readonly \Blog\Security\CsrfProtection $csrfProtection,
        private readonly \Blog\Security\SecurityLogger $logger
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Session should be started by SessionMiddleware


        $method = $request->getMethod();
        $path = $request->getUri()->getPath();

        // Generate token if not exists (delegated to CsrfProtection)
        $token = $this->csrfProtection->getToken();

        // Skip CSRF validation for safe methods and exempted paths
        $isExempt = in_array($path, self::EXEMPT_PATHS, true);

        if (in_array($method, ['GET', 'HEAD', 'OPTIONS']) || str_starts_with($path, '/api/') && $isExempt) {
            // Add token to request attributes for templates
            $request = $request->withAttribute('csrf_token', $token);

            return $handler->handle($request);
        }

        // Validate CSRF token for unsafe methods
        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $body = $request->getParsedBody();
            $token = $body['csrf_token'] ?? $request->getHeaderLine('X-CSRF-Token');

            if (!$this->csrfProtection->validateToken($token ?? '')) {
                // Get user ID from session for logging
                $userId = $_SESSION['user_id'] ?? null;
                $ip = $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown';

                // Log CSRF failure with proper format
                $this->logger->logCsrfValidationFailure($userId, $ip, $path);

                return $this->createCsrfErrorResponse();
            }

            // Single-use token: regenerate after successful validation
            $this->csrfProtection->regenerateToken();
        }

        // Add token to request attributes for templates
        $request = $request->withAttribute('csrf_token', $this->csrfProtection->getToken());

        return $handler->handle($request);
    }



    private function createCsrfErrorResponse(): ResponseInterface
    {
        $response = new Response();

        // Always return JSON response as specified
        $response->getBody()->write(json_encode([
            "error" => "CSRF validation failed",
            "code" => "CSRF_TOKEN_INVALID",
            "message" => "Invalid or missing CSRF token. Please refresh the page and try again.",
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(403); // 403 Forbidden
    }


}
