<?php
// public/debug_delete_direct.php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

echo "<pre>";
echo "=== Direct Debug of Delete Endpoint ===\n\n";

try {
    // 1. Setup session for mark admin
    session_start();
    $_SESSION['user_id'] = 'test-admin-id';
    $_SESSION['user_role'] = 'ROLE_MARK';
    $_SESSION['user_email'] = 'admin@admin.com';
    $_SESSION['mark_session'] = true;
    
    echo "1. Session setup for mark admin\n";
    
    // 2. Get container
    $containerFactory = require __DIR__ . '/../config/container.php';
    $container = $containerFactory();
    echo "2. Container created\n";
    
    // 3. Get ArticlesController
    echo "3. Getting ArticlesController...\n";
    $controller = $container->get(\Blog\Infrastructure\Http\Controller\Mark\ArticlesController::class);
    echo "   ✓ Controller: " . get_class($controller) . "\n";
    
    // 4. Check if delete method exists
    echo "4. Checking delete method...\n";
    if (method_exists($controller, 'delete')) {
        echo "   ✓ delete() method exists\n";
    } else {
        echo "   ✗ delete() method NOT found\n";
        echo "   Available methods:\n";
        foreach (get_class_methods($controller) as $method) {
            echo "   - $method\n";
        }
    }
    
    // 5. Create mock request
    echo "5. Creating mock request...\n";
    $request = new \Nyholm\Psr7\ServerRequest(
        'DELETE',
        '/mark/articles/54/delete',
        ['Content-Type' => 'application/json'],
        null,
        '1.1',
        ['REMOTE_ADDR' => '127.0.0.1']
    );
    
    // Add route parameter 'id'
    $request = $request->withAttribute('id', '54');
    
    echo "6. Calling delete method...\n";
    
    // 6. Call delete method
    $response = $controller->delete($request);
    
    echo "   ✓ Delete method executed\n";
    echo "   Response status: " . $response->getStatusCode() . "\n";
    echo "   Response headers:\n";
    foreach ($response->getHeaders() as $name => $values) {
        echo "   - $name: " . implode(', ', $values) . "\n";
    }
    
} catch (Throwable $e) {
    echo "\n✗ ERROR CAUGHT:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
    
    // Special handling for common errors
    if ($e instanceof \Error && strpos($e->getMessage(), 'Call to undefined method') !== false) {
        echo "\n=== UNDEFINED METHOD ANALYSIS ===\n";
        $matches = [];
        if (preg_match('/Call to undefined method (.*?)::(.*?)\(\)/', $e->getMessage(), $matches)) {
            $class = $matches[1];
            $method = $matches[2];
            echo "Missing method: $class::$method()\n";
            
            // Check if class exists
            if (class_exists($class)) {
                echo "Class exists\n";
                echo "Available methods in $class:\n";
                foreach (get_class_methods($class) as $availableMethod) {
                    echo " - $availableMethod\n";
                }
            } else {
                echo "Class does not exist\n";
            }
        }
    }
}

echo "\n=== End Debug ===\n";
echo "</pre>";