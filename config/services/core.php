<?php

declare(strict_types=1);

use Blog\Core\UseCaseHandler;
use Blog\Core\UseCaseMapper;
use Blog\Database\Database;
use Blog\Database\DatabaseManager;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use ResponsiveSk\Slim4Paths\Paths;

return [
    // HTTP Factories (PSR-17)
    Psr17Factory::class => fn () => new Psr17Factory(),

    // Database
    'database' => fn () => \Blog\Database\DatabaseManager::getConnection('articles'),
    Database::class => fn () => DatabaseManager::getConnection(),

    // Paths
    Paths::class => fn () => new Paths(__DIR__ . '/../../'), // Adjust path for new location

    // === CORE USE-CASE TOOLS ===
    UseCaseMapper::class => fn () => new UseCaseMapper(),
    UseCaseHandler::class => fn (ContainerInterface $c) => new UseCaseHandler($c),

    // Security Logger (File based)
    \Blog\Security\SecurityLogger::class => fn () => new \Blog\Security\SecurityLogger(
        \Blog\Infrastructure\Paths::basePath() . '/data/logs/security.log'
    ),

    // Error Logger (File based)
    \Blog\Application\Audit\ErrorLogger::class => fn () => new \Blog\Application\Audit\ErrorLogger(
        \Blog\Infrastructure\Paths::basePath() . '/data/logs/error.log'
    ),
];