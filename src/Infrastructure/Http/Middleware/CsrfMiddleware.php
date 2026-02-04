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
    private string $tokenName = 'csrf_token';
    private int $tokenLength = 32;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Start session if not started (use same session name as SessionMiddleware)
        if (session_status() === PHP_SESSION_NONE) {
            $sessionName = $_ENV['SESSION_NAME'] ?? 'blog_session';
            session_name($sessionName);
            session_start();
        }

        $method = $request->getMethod();
        $path = $request->getUri()->getPath();

        // Generate token if not exists (for all requests)
        if (!isset($_SESSION[$this->tokenName])) {
            $_SESSION[$this->tokenName] = $this->generateToken();
        }

        // Skip CSRF validation for safe methods and API endpoints
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS']) || str_starts_with($path, '/api/')) {
            // Add token to request attributes for templates
            $request = $request->withAttribute($this->tokenName, $_SESSION[$this->tokenName]);

            return $handler->handle($request);
        }

        // Validate CSRF token for unsafe methods
        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $body = $request->getParsedBody();
            $token = $body[$this->tokenName] ?? $request->getHeaderLine('X-CSRF-Token');

            if (!$this->validateToken($token)) {
                return $this->createCsrfErrorResponse();
            }
        }

        // Add token to request attributes for templates
        $request = $request->withAttribute($this->tokenName, $_SESSION[$this->tokenName]);

        return $handler->handle($request);
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes($this->tokenLength));
    }

    private function validateToken(?string $token): bool
    {
        if ($token === null) {
            return false;
        }

        return hash_equals($_SESSION[$this->tokenName] ?? '', $token);
    }

    private function createCsrfErrorResponse(): ResponseInterface
    {
        $response = new Response();

        // Check if AJAX request
        if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
            $response->getBody()->write(json_encode([
                'error' => 'Invalid CSRF token',
                'code' => 'INVALID_CSRF_TOKEN',
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(419); // 419 Authentication Timeout (custom for CSRF)
        }

        // For regular requests, redirect back with error
        $response->getBody()->write('
            <!DOCTYPE html>
            <html>
            <head><title>CSRF Error</title></head>
            <body>
                <h1>Invalid CSRF Token</h1>
                <p>Please go back and try again.</p>
                <button onclick="history.back()">Go Back</button>
            </body>
            </html>
        ');

        return $response->withStatus(419);
    }

    public function getTokenName(): string
    {
        return $this->tokenName;
    }

    public function regenerateToken(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION[$this->tokenName] = $this->generateToken();
    }
}
