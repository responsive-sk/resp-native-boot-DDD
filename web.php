<?php
// web_fixed_debugbar.php - OPRAVENÁ VERZIA
declare(strict_types=1);

// 1. Načítaj boot
require_once __DIR__ . '/boot.php';

// 2. Session setup
$sessionName = $_ENV['SESSION_NAME'] ?? 'resp_session';
session_name($sessionName);

// 3. Vytvor kontajner
$containerFactory = require __DIR__ . '/config/container.php';
$container = $containerFactory();

// 4. Vytvor app
$app = $container->get(Blog\Core\Application::class);

// 5. **BLOG DEBUGBAR** - použijeme náš vlastný middleware
$useDebugBar = true; // Vždy testovať

if (
    $useDebugBar &&
    class_exists('\DebugBar\StandardDebugBar') &&
    class_exists('\Blog\Infrastructure\Http\Middleware\BlogDebugBarMiddleware')
) {

    echo "<!-- Blog DebugBar initialization -->\n";

    try {
        // Pridať náš vlastný DebugBar middleware
        $blogDebugBarMiddleware = new \Blog\Infrastructure\Http\Middleware\BlogDebugBarMiddleware();
        $app->add($blogDebugBarMiddleware);

        echo "<!-- BlogDebugBarMiddleware added to app -->\n";

        // Spracuj request normálne - app sa postará o middleware
        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $creator = new \Nyholm\Psr7Server\ServerRequestCreator(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory
        );
        $request = $creator->fromGlobals();
        $response = $app->handle($request);

        // Pošli response
        $app->emit($response);

        exit; // Ukončiť

    } catch (\Throwable $e) {
        error_log("Blog DebugBar error: " . $e->getMessage());
        echo "<!-- Blog DebugBar error: " . $e->getMessage() . " -->\n";
        // Pokračujeme bez DebugBar
    }
}

// 6. Ak DebugBar zlyhal alebo nie je povolený, spusti normálne
$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$creator = new \Nyholm\Psr7Server\ServerRequestCreator(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);
$request = $creator->fromGlobals();
$response = $app->handle($request);

// 7. Pošli response
$app->emit($response);