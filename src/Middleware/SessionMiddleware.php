<?php

declare(strict_types=1);

namespace Blog\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SessionMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if (session_status() === PHP_SESSION_NONE) {
            // Set session name from environment
            $sessionName = $_ENV['SESSION_NAME'] ?? 'blog_session';
            session_name($sessionName);

            // Get session lifetime from environment (default 1 hour)
            $sessionLifetime = (int) ($_ENV['SESSION_LIFETIME'] ?? 3600);

            session_start([
                'cookie_secure' => isset($_SERVER['HTTPS']),
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
                'use_strict_mode' => true,
                'cookie_lifetime' => $sessionLifetime,
            ]);
        }

        return $handler->handle($request);
    }
}
