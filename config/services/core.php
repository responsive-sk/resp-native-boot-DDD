<?php

declare(strict_types=1);

use Blog\Core\Application;
use Blog\Core\UseCaseHandler;
use Blog\Core\UseCaseMapper;
use Blog\Database\Database;
use Blog\Database\DatabaseManager;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use ResponsiveSk\Slim4Paths\Paths;
use Blog\Domain\Audit\Repository\AuditLogRepository;

return [
        // === CORE APPLICATION ===
    Application::class => function (ContainerInterface $c) {
        // MINIMÁLNÍ seznam middleware
        $middlewares = [];

        // 1. Error Handler
        if ($c->has(\Blog\Infrastructure\Http\Middleware\ErrorHandlerMiddleware::class)) {
            $middlewares[] = $c->get(\Blog\Infrastructure\Http\Middleware\ErrorHandlerMiddleware::class);
        }

        // 2. Session Middleware
        $sessionClasses = [
            \ResponsiveSk\Slim4Session\Middleware\SessionMiddleware::class,
            \Blog\Infrastructure\Http\Middleware\SessionMiddleware::class,
            \Blog\Middleware\SessionMiddleware::class,
        ];

        foreach ($sessionClasses as $sessionClass) {
            if ($c->has($sessionClass)) {
                $middlewares[] = $c->get($sessionClass);
                break;
            }
        }

        // 3. Input Sanitization Middleware (after session, before CSRF)
        if ($c->has(\Blog\Infrastructure\Http\Middleware\InputSanitizationMiddleware::class)) {
            $middlewares[] = $c->get(\Blog\Infrastructure\Http\Middleware\InputSanitizationMiddleware::class);
        }

        // 3. HTMX Middleware (pred routerom)
        if ($c->has(\Blog\Middleware\HtmxMiddleware::class)) {
            $middlewares[] = $c->get(\Blog\Middleware\HtmxMiddleware::class);
        }

        // 4. Router Middleware
        if ($c->has(\Blog\Core\RouterMiddleware::class)) {
            $middlewares[] = $c->get(\Blog\Core\RouterMiddleware::class);
        } else {
            throw new RuntimeException('RouterMiddleware is required but not found');
        }

        return new Application($middlewares);
    },

        // HTTP Factories (PSR-17)
    Psr17Factory::class => fn() => new Psr17Factory(),

    // Database
    'database' => fn() => \Blog\Database\DatabaseManager::getConnection('articles'),
    Database::class => fn() => DatabaseManager::getConnection(),

        // Paths
    Paths::class => fn() => new Paths(dirname(__DIR__, 2)),

        // === CORE USE-CASE TOOLS ===
    UseCaseMapper::class => fn() => new UseCaseMapper(),
    UseCaseHandler::class => fn(ContainerInterface $c) => new UseCaseHandler($c),

    // === MAPPERS ===
    \Blog\Application\Blog\ArticleResponseMapper::class => fn() => new \Blog\Application\Blog\ArticleResponseMapper(),

    // Security Logger (File based)
    \Blog\Security\SecurityLogger::class => fn() => new \Blog\Security\SecurityLogger(
        \Blog\Infrastructure\Paths::basePath() . '/data/logs/security.log'
    ),

    // Audit Logger - Injects Repository
    \Blog\Application\Audit\AuditLogger::class => fn(ContainerInterface $c) => new \Blog\Application\Audit\AuditLogger(
        $c->get(AuditLogRepository::class) // This must be defined in services/repositories.php
    ),

    // Error Logger (File based)
    \Blog\Application\Audit\ErrorLogger::class => fn() => new \Blog\Application\Audit\ErrorLogger(
        \Blog\Infrastructure\Paths::basePath() . '/data/logs/error.log'
    ),
];
