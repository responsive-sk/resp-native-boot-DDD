<?php
// test_fixed_boot.php
require_once __DIR__ . '/boot.php';

echo "Boot.php test:\n";

// Test kontajneru
if ($container instanceof \Psr\Container\ContainerInterface) {
    echo "✓ Container created successfully\n";
    
    // Test session
    if ($container->has(\ResponsiveSk\Slim4Session\SessionInterface::class)) {
        echo "✓ Session service found in container\n";
        
        $session = $container->get(\ResponsiveSk\Slim4Session\SessionInterface::class);
        echo "✓ Session object: " . get_class($session) . "\n";
    } else {
        echo "✗ Session service NOT found in container\n";
    }
    
    // Test Authorization
    try {
        $user = \Blog\Security\Authorization::getUser();
        echo "✓ Authorization::getUser(): " . json_encode($user) . "\n";
    } catch (Exception $e) {
        echo "✗ Authorization error: " . $e->getMessage() . "\n";
    }
} else {
    echo "✗ Container not created\n";
}