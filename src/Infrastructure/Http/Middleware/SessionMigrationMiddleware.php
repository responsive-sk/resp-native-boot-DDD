<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use ResponsiveSk\Slim4Session\SessionInterface;

/**
 * Session Migration Middleware.
 * handles initializing and migrating session security features.
 */
class SessionMigrationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly SessionInterface $session,
        private readonly LoggerInterface $logger
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Session should be started by SessionMiddleware prior to this
        if ($this->session->isStarted()) {
            $this->migrateSessionIfNeeded();
        }

        return $handler->handle($request);
    }

    private function migrateSessionIfNeeded(): void
    {
        // Session Migration: Initialize default values if missing
        if (!$this->session->has('last_activity')) {
            $now = time();
            $this->session->set('last_activity', $now);
            $this->session->set('created_at', $now);

            // Generate new CSRF token if missing
            if (!$this->session->has('csrf_token')) {
                $this->session->set('csrf_token', bin2hex(random_bytes(32)));
            }

            // Bind to User-Agent
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $this->session->set('user_agent_hash', hash('sha256', $_SERVER['HTTP_USER_AGENT']));
            }

            $this->logger->info('Session migrated/initialized for session ID ' . $this->session->getId());
        }
    }
}
