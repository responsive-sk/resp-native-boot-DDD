<?php

declare(strict_types=1);

/**
 * Aggregated application configuration.
 *
 * Groups small config files under a single entry point to
 * reduce clutter in the root config directory.
 */

return [
    'debugbar' => require __DIR__ . '/app/debugbar.php',
    'session' => require __DIR__ . '/app/session.php',
    'pages' => require __DIR__ . '/app/pages.php',
    'paths' => require __DIR__ . '/app/paths.php',
    'ratelimit' => require __DIR__ . '/app/ratelimit.php',
    'password_strength' => require __DIR__ . '/app/password_strength.php',
];

