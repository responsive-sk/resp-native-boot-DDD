<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Middleware;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionTimeoutMiddleware implements MiddlewareInterface
{
    private int $timeout;
    private string $redirectUrl;

    public function __construct(int $timeout = 1800, string $redirectUrl = '/login')
    {
        $this->timeout = $timeout;
        $this->redirectUrl = $redirectUrl;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Start session if not started (should be started by SessionMiddleware)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $path = $request->getUri()->getPath();

        // Determine timeout based on path
        $timeout = $this->timeout; // Default from config (30m or 60m)
        if (str_starts_with($path, '/mark')) {
            $timeout = 1800; // Mark admin always 30 min
        }

        // If configured via constructor, use that as base?
        // We typically override via path.
        // For now hardcoded per user instructions or configurable.
        // Let's rely on the path logic.

        $now = time();
        $lastActivity = $_SESSION['last_activity'] ?? $now;

        // Check if timed out
        if (($now - $lastActivity) > $timeout) {
            // Avoid checking timeout on the ping endpoint itself if it's too late?
            // No, ping should fail if session is already dead.

            // Unset session and destroy
            session_unset();
            session_destroy();

            // Handle AJAX / API requests with 401 JSON
            if ($this->isAjax($request) || $this->isApi($request)) {
                $response = new Response();
                $response->getBody()->write(json_encode(['error' => 'Session expired', 'code' => 'SESSION_EXPIRED']));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(401);
            }

            // Handle standard request with redirect
            $response = new Response();

            return $response
                ->withHeader('Location', $this->redirectUrl . '?expired=1')
                ->withStatus(302);
        }

        // Update last activity time
        $_SESSION['last_activity'] = $now;

        return $handler->handle($request);
    }

    private function isAjax(ServerRequestInterface $request): bool
    {
        return strtolower($request->getHeaderLine('X-Requested-With')) === 'xmlhttprequest';
    }

    private function isApi(ServerRequestInterface $request): bool
    {
        return str_starts_with($request->getUri()->getPath(), '/api/');
    }
}
