<?php
// test_login_flow.php
require_once __DIR__ . '/boot.php';

use Blog\Application\User\LoginUser;
use Blog\Database\DatabaseManager;
use Blog\Domain\User\ValueObject\Email;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;

echo "=== Testing Login Flow ===\n\n";

// Create the dependencies
$db = DatabaseManager::getConnection('users');
$userRepo = new DoctrineUserRepository($db);
$loginUser = new LoginUser($userRepo);

// Test login with admin credentials
$testEmail = 'admin@admin.com';
$testPassword = 'admin'; // You might need to use the actual password

echo "1. Attempting login for: {$testEmail}\n";

try {
    // First, let's check what's in the database
    $row = $db->fetchAssociative('SELECT * FROM users WHERE email = ?', [$testEmail]);
    if ($row) {
        echo "   Found in DB:\n";
        echo "   ID: " . bin2hex($row['id']) . "\n";
        echo "   Email: {$row['email']}\n";
        echo "   Role: '{$row['role']}'\n";
        echo "   Password hash: {$row['password_hash']}\n";
    }
    
    // Try to login
    $user = $loginUser($testEmail, $testPassword);
    echo "\n2. Login successful!\n";
    echo "   User ID: " . $user->id()->toString() . "\n";
    echo "   User Email: " . $user->email()->toString() . "\n";
    echo "   User Role from entity: " . $user->role()->toString() . "\n";
    echo "   Role isMark(): " . ($user->role()->isMark() ? 'true' : 'false') . "\n";
    
    // Now simulate what AuthController does
    echo "\n3. Simulating AuthController::setupUserSession():\n";
    
    // Start fresh session
    session_start();
    session_regenerate_id(true);
    
    // Store user data (as AuthController does)
    $_SESSION['user_id'] = $user->id()->toString();
    $_SESSION['user_role'] = $user->role()->toString();
    $_SESSION['user_email'] = $user->email()->toString();
    $_SESSION['last_activity'] = time();
    
    echo "   Stored in session:\n";
    echo "   user_id: {$_SESSION['user_id']}\n";
    echo "   user_role: '{$_SESSION['user_role']}'\n";
    echo "   user_email: {$_SESSION['user_email']}\n";
    
    // Now test Authorization class
    echo "\n4. Testing Authorization class:\n";
    
    // We need to include and test Authorization
    require_once __DIR__ . '/src/Security/Authorization.php';
    
    echo "   Authorization::isAuthenticated(): " . 
         (\Blog\Security\Authorization::isAuthenticated() ? 'true' : 'false') . "\n";
    
    $authUser = \Blog\Security\Authorization::getUser();
    echo "   Authorization::getUser(): " . json_encode($authUser) . "\n";
    
    if ($authUser) {
        echo "   Stored role: '{$authUser['role']}'\n";
        echo "   Checking hasRole('ROLE_MARK'): " . 
             (\Blog\Security\Authorization::hasRole('ROLE_MARK') ? 'true' : 'false') . "\n";
        echo "   Checking isMark(): " . 
             (\Blog\Security\Authorization::isMark() ? 'true' : 'false') . "\n";
        
        // Debug the exact comparison
        echo "\n   DEBUG comparison:\n";
        echo "   Session role: '{$authUser['role']}'\n";
        echo "   Check role: 'ROLE_MARK'\n";
        echo "   Exact match (===): " . ($authUser['role'] === 'ROLE_MARK' ? 'true' : 'false') . "\n";
        echo "   Length comparison: " . strlen($authUser['role']) . " vs " . strlen('ROLE_MARK') . "\n";
        
        // Hex comparison
        echo "   Hex comparison:\n";
        echo "   Session role hex: " . bin2hex($authUser['role']) . "\n";
        echo "   ROLE_MARK hex: " . bin2hex('ROLE_MARK') . "\n";
        echo "   Hex match: " . (bin2hex($authUser['role']) === bin2hex('ROLE_MARK') ? 'true' : 'false') . "\n";
    }
    
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== End Test ===\n";