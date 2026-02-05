<?php

// config/debugbar.php

declare(strict_types=1);

use ResponsiveSk\PhpDebugBarMiddleware\ConfigProvider;
use Blog\Infrastructure\DebugBar\BlogDebugBarStyles;

return [
    'debugbar' => [
        'enabled' => ($_ENV['APP_ENV'] ?? 'development') === 'development',
        
        // Blog branding configuration
        'branding' => [
            'title' => 'Blog Debug',
            'primary_color' => '#2563eb',
            'secondary_color' => '#1e40af',
            'accent_color' => '#f59e0b',
        ],
        
        // Custom CSS with blog branding
        'custom_css' => BlogDebugBarStyles::getCustomCss(),
        
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