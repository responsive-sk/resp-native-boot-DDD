<?php

declare(strict_types=1);

/**
 * Path Configuration (moved from config/paths.php)
 *
 * This file defines custom path mappings for the application.
 * Paths are auto-detected from environment or use defaults.
 */

$basePath = dirname(__DIR__, 2);

return [
    'base' => $basePath,
    'data' => $basePath . '/data',
    'config' => $basePath . '/config',
    'public' => $basePath . '/public',
    'resources' => $basePath . '/resources',
    'src' => $basePath . '/src',
    'vendor' => $basePath . '/vendor',
    'scripts' => $basePath . '/scripts',
    'tests' => $basePath . '/tests',

    // Storage paths
    'storage' => $basePath . '/data',
    'logs' => $basePath . '/data/logs',
    'cache' => $basePath . '/data/cache',

    // Database paths
    'db.articles' => $basePath . '/data/articles.db',
    'db.users' => $basePath . '/data/users.db',
    'db.forms' => $basePath . '/data/forms.db',
];

