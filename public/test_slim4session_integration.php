<?php
// public/test_slim4session_integration.php
require_once __DIR__ . '/../boot.php';

use ResponsiveSk\Slim4Session\SessionInterface;

echo "<pre>";
echo "=== Testing slim4-session Integration ===\n\n";

try {
    $containerFactory = require __DIR__ . '/../config/container.php';
    $container = $containerFactory();
    
    echo "1. Container created\n";
    
    // Check SessionInterface
    if ($container->has(SessionInterface::class)) {
        $session = $container->get(SessionInterface::class);
        echo "2. SessionInterface found: " . get_class($session) . "\n";
        
        // Test session methods
        $session->set('test_key', 'test_value');
        echo "3. session->set() works\n";
        
        $value = $session->get('test_key');
        echo "4. session->get() works: $value\n";
        
        echo "5. session->has('test_key'): " . ($session->has('test_key') ? 'true' : 'false') . "\n";
        
        // Test Authorization
        echo "\n6. Testing Authorization...\n";
        $authSession = \Blog\Security\Authorization::getUser();
        echo "   Authorization::getUser(): " . json_encode($authSession) . "\n";
        
        // Test AuthController
        echo "\n7. Testing AuthController...\n";
        if ($container->has(\Blog\Infrastructure\Http\Controller\Web\AuthController::class)) {
            $authController = $container->get(\Blog\Infrastructure\Http\Controller\Web\AuthController::class);
            echo "   AuthController: " . get_class($authController) . "\n";
            echo "   SessionInterface injected: " . (method_exists($authController, 'getSession') ? 'YES' : 'NO') . "\n";
        } else {
            echo "   ✗ AuthController NOT in container\n";
        }
        
    } else {
        echo "2. ✗ SessionInterface NOT in container\n";
    }
    
} catch (Throwable $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== End Test ===\n";
echo "</pre>";
