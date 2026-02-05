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

// 5. **OPRAVENÝ DEBUGBAR** - správne parametre
$useDebugBar = true; // Vždy testovať

if ($useDebugBar && 
    class_exists('\DebugBar\StandardDebugBar') && 
    class_exists('\ResponsiveSk\PhpDebugBarMiddleware\DebugBarMiddleware')) {
    
    echo "<!-- DebugBar initialization -->\n";
    
    try {
        // 1. Vytvor DebugBar
        $debugBar = new \DebugBar\StandardDebugBar();
        
        // 2. Pridaj PDO collector ak existuje DB
        try {
            if (class_exists('\Blog\Database\DatabaseManager')) {
                $db = \Blog\Database\DatabaseManager::getConnection();
                $debugBar->addCollector(new \DebugBar\DataCollector\PDO\PDOCollector($db->getPdo()));
            }
        } catch (\Throwable $e) {
            // Ignore DB errors
            echo "<!-- PDO collector skipped: " . $e->getMessage() . " -->\n";
        }
        
        // 3. **OPRAVA:** Pridaj assetPath parameter!
        $debugBarMiddleware = new \ResponsiveSk\PhpDebugBarMiddleware\DebugBarMiddleware(
            $debugBar,  // Prvý parameter: DebugBar inštancia
            '/debugbar' // Druhý parameter: asset path (povinný!)
        );
        
        // 4. Pridať middleware do app **SPRÁVNE** - cez $app->add()
        $app->add($debugBarMiddleware);
        
        echo "<!-- DebugBarMiddleware added to app -->\n";
        
        // 5. Assets route už nie je potrebný - middleware sa postará o assets
        
        // 6. Spracuj request normálne - app sa postará o middleware
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
        
        exit; // Ukončiť
        
    } catch (\Throwable $e) {
        error_log("DebugBar error: " . $e->getMessage());
        echo "<!-- DebugBar error: " . $e->getMessage() . " -->\n";
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