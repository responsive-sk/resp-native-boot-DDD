<?php

require 'vendor/autoload.php';

echo "=== ROUTER ANALYSIS ===\n\n";

// Check Router class
if (class_exists('Blog\Core\Router')) {
    echo "✅ Blog\Core\Router exists\n";

    $reflection = new ReflectionClass('Blog\Core\Router');
    echo "Methods:\n";
    foreach ($reflection->getMethods() as $method) {
        echo "  - " . $method->getName() . "()\n";
    }
} else {
    echo "❌ Blog\Core\Router not found\n";
}

// Check what's in container as 'router'
echo "\n=== CONTAINER ROUTER ===\n";
try {
    $containerFactory = require 'config/container.php';
    $container = $containerFactory();

    if ($container->has('router')) {
        $router = $container->get('router');
        echo "✅ Container has 'router'\n";
        echo "Type: " . get_class($router) . "\n";

        // Check router methods
        $routerReflection = new ReflectionClass($router);
        echo "Router methods:\n";
        foreach ($routerReflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (!$method->isConstructor()) {
                echo "  - " . $method->getName() . "()\n";
            }
        }
    } else {
        echo "❌ Container does NOT have 'router'\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
