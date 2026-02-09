<?php

declare(strict_types=1);

namespace Blog\Middleware;

use Blog\Security\HttpsDetector;
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

            // Determine if session cookie should persist (default: true)
            $sessionCookiePersistent = filter_var($_ENV['SESSION_COOKIE_PERSISTENT'] ?? 'true', FILTER_VALIDATE_BOOLEAN);

            // Set session.gc_maxlifetime (server-side garbage collection)
            ini_set('session.gc_maxlifetime', (string) $sessionLifetime);

            // Determine cookie_lifetime: 0 means expires when browser closes
            $cookieLifetime = $sessionCookiePersistent ? $sessionLifetime : 0;

            session_start([
                'cookie_secure' => HttpsDetector::isHttps(),
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
                'use_strict_mode' => true,
                'cookie_lifetime' => $cookieLifetime,
            ]);
        }

        return $handler->handle($request);
    }
}
