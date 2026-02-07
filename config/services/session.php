<?php

declare(strict_types=1);

use Blog\Security\HttpsDetector;
use Psr\Container\ContainerInterface;
use ResponsiveSk\Slim4Session\Middleware\SessionMiddleware;
use ResponsiveSk\Slim4Session\SessionFactory;
use ResponsiveSk\Slim4Session\SessionInterface;

return [
    SessionInterface::class => function (ContainerInterface $c) {
        $config = require __DIR__ . '/../session.php';
        
        // Add database connection for database storage
        if ($config['storage'] === 'database') {
            $config['database'] = array_merge($config['database'] ?? [], [
                'connection' => $c->get('database'),
            ]);
        }

        // Override cookie_secure with proper HTTPS detection
        if (isset($config['security']['cookie_secure']) && $config['security']['cookie_secure'] === 'auto') {
            $config['security']['cookie_secure'] = HttpsDetector::isHttps();
        }

        return SessionFactory::create($config);
    },

    SessionMiddleware::class => fn (ContainerInterface $c) => new SessionMiddleware(
        $c->get(SessionInterface::class)
    ),
];
