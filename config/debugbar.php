<?php
// config/debugbar.php

declare(strict_types=1);

use ResponsiveSk\PhpDebugBarMiddleware\ConfigProvider;

return [
    'debugbar' => [
        'enabled' => ($_ENV['APP_ENV'] ?? 'development') === 'development',
        'collectors' => [
            'messages' => true,
            'time' => true,
            'memory' => true,
            'exceptions' => true,
            'request' => true,
            'session' => true,
            'pdo' => true, // Automaticky detekuje PDO connections
        ],
        'asset_path' => '/debugbar',
        'options' => [
            'enable_jquery_noconflict' => false,
        ],
    ],
    
    // Pridáme služby do kontajnera
    'dependencies' => [
        'factories' => [
            \ResponsiveSk\PhpDebugBarMiddleware\DebugBarMiddleware::class => 
                \ResponsiveSk\PhpDebugBarMiddleware\DebugBarMiddlewareFactory::class,
            \ResponsiveSk\PhpDebugBarMiddleware\DebugBarAssetsHandler::class => 
                \ResponsiveSk\PhpDebugBarMiddleware\DebugBarAssetsHandlerFactory::class,
        ],
    ],
];