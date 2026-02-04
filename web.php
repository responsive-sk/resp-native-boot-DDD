<?php

declare(strict_types=1);

namespace Blog;

use Blog\Core\Application;

require __DIR__ . '/vendor/autoload.php';

return static function (?string $env = null) {
    // Normalize environment
    if ($env === null || $env === '') {
        $env = getenv('APP_ENV') ?: 'dev';
    }

    // Error handling
    if ($env === 'production') {
        error_reporting(0);
        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
    } else {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
    }

    // DI Container
    $containerFactory = require __DIR__ . '/config/container.php';
    $container = $containerFactory();

    // Get middlewares from container
    $middlewares = $container->get('middlewares');

    // Register routes (this adds them to router in container)


    // Create Application with middleware stack
    $app = new Application($middlewares);

    return $app;
};
