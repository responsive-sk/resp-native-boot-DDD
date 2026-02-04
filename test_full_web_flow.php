<?php
// test_auth_session_only.php
require_once __DIR__ . '/boot.php';

use Blog\Application\User\LoginUser;
use Blog\Database\DatabaseManager;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;

echo "=== Testing Authentication & Session Only ===\n\n";

// Setup minimal dependencies
$db = DatabaseManager::getConnection('users');
$userRepo = new DoctrineUserRepository($db);
$loginUser = new LoginUser($userRepo);

// Test credentials
$email = 'admin@admin.com';
$password = 'admin123';

echo "1. Testing login:\n";
try {
    $user = $loginUser($email, $password);
    echo "   SUCCESS - User logged in\n";
    echo "   User ID: " . $user->id()->toString() . "\n";
    echo "   User Role: " . $user->role()->toString() . "\n";
    echo "   isMark(): " . ($user->role()->isMark() ? 'true' : 'false') . "\n";
    
    echo "\n2. Testing session storage:\n";
    
    // Start fresh session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Clear any existing session
    $_SESSION = [];
    
    // Simulate what AuthController does
    $_SESSION['user_id'] = $user->id()->toString();
    $_SESSION['user_role'] = $user->role()->toString();
    $_SESSION['user_email'] = $user->email()->toString();
    $_SESSION['last_activity'] = time();
    
    echo "   Session data stored:\n";
    echo "   user_id: " . $_SESSION['user_id'] . "\n";
    echo "   user_role: '" . $_SESSION['user_role'] . "'\n";
    echo "   user_email: " . $_SESSION['user_email'] . "\n";
    echo "   Session ID: " . session_id() . "\n";
    
    // Write and close session
    session_write_close();
    
    echo "\n3. Testing session retrieval in new 'request':\n";
    
    // Simulate new request - start session with same ID
    $savedSessionId = session_id();
    session_id($savedSessionId);
    session_start();
    
    echo "   New request session ID: " . session_id() . "\n";
    echo "   Retrieved user_role: '" . ($_SESSION['user_role'] ?? 'NOT FOUND') . "'\n";
    
    // Test Authorization class
    echo "\n4. Testing Authorization class:\n";
    
    // Include Authorization class
    require_once __DIR__ . '/src/Security/Authorization.php';
    
    // Test methods
    echo "   isAuthenticated(): " . 
         (\Blog\Security\Authorization::isAuthenticated() ? 'true' : 'false') . "\n";
    
    $authUser = \Blog\Security\Authorization::getUser();
    echo "   getUser(): " . json_encode($authUser) . "\n";
    
    if ($authUser) {
        echo "   Role from Authorization: '" . $authUser['role'] . "'\n";
        echo "   hasRole('ROLE_MARK'): " . 
             (\Blog\Security\Authorization::hasRole('ROLE_MARK') ? 'true' : 'false') . "\n";
        echo "   isMark(): " . 
             (\Blog\Security\Authorization::isMark() ? 'true' : 'false') . "\n";
        
        // Debug comparison
        if ($authUser['role'] !== 'ROLE_MARK') {
            echo "\n   DEBUG - Why doesn't it match?\n";
            echo "   Stored: '" . $authUser['role'] . "'\n";
            echo "   Expected: 'ROLE_MARK'\n";
            echo "   Lengths: " . strlen($authUser['role']) . " vs " . strlen('ROLE_MARK') . "\n";
            echo "   Hex: " . bin2hex($authUser['role']) . " vs " . bin2hex('ROLE_MARK') . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== End Test ===\n";