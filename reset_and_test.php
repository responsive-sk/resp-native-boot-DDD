<?php
// reset_and_test.php
require_once __DIR__ . '/boot.php';

use Blog\Database\DatabaseManager;

$db = DatabaseManager::getConnection('users');

echo "=== Password Reset and Test ===\n\n";

// 1. Check current state
echo "1. Checking current admin user:\n";
$row = $db->fetchAssociative('SELECT * FROM users WHERE email = ?', ['admin@admin.com']);
if ($row) {
    echo "   Found admin user:\n";
    echo "   ID: " . bin2hex($row['id']) . "\n";
    echo "   Email: {$row['email']}\n";
    echo "   Role: '{$row['role']}'\n";
    echo "   Current hash: {$row['password_hash']}\n";
    echo "   Hash algorithm: " . (str_starts_with($row['password_hash'], '$2y$') ? 'bcrypt' : 'unknown') . "\n";
} else {
    echo "   Admin user not found!\n";
    exit(1);
}

// 2. Reset password
echo "\n2. Resetting password to 'admin123':\n";
$newPassword = 'admin123';
$passwordHash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);

$result = $db->update('users', 
    ['password_hash' => $passwordHash],
    ['email' => 'admin@admin.com']
);

if ($result) {
    echo "   Password updated successfully\n";
    echo "   New hash: {$passwordHash}\n";
    
    // Verify with PHP's password_verify
    echo "   PHP password_verify test: ";
    echo password_verify($newPassword, $passwordHash) ? 'SUCCESS' : 'FAILED';
    echo "\n";
} else {
    echo "   Failed to update password\n";
    exit(1);
}

// 3. Test with User entity
echo "\n3. Testing with User entity:\n";
try {
    // We need to recreate the user entity to test
    require_once __DIR__ . '/src/Domain/User/ValueObject/HashedPassword.php';
    require_once __DIR__ . '/src/Domain/User/ValueObject/Email.php';
    require_once __DIR__ . '/src/Domain/User/ValueObject/UserId.php';
    require_once __DIR__ . '/src/Domain/User/ValueObject/UserRole.php';
    require_once __DIR__ . '/src/Domain/User/Entity/User.php';
    
    // Get updated user data
    $row = $db->fetchAssociative('SELECT * FROM users WHERE email = ?', ['admin@admin.com']);
    
    // Create value objects
    $userId = \Blog\Domain\User\ValueObject\UserId::fromBytes($row['id']);
    $email = \Blog\Domain\User\ValueObject\Email::fromString($row['email']);
    $password = \Blog\Domain\User\ValueObject\HashedPassword::fromHash($row['password_hash']);
    $role = \Blog\Domain\User\ValueObject\UserRole::fromString($row['role']);
    
    // Create user entity
    $user = \Blog\Domain\User\Entity\User::reconstitute(
        $userId,
        $email,
        $password,
        $role,
        new DateTimeImmutable($row['created_at'])
    );
    
    echo "   User entity created successfully\n";
    echo "   Role: " . $user->role()->toString() . "\n";
    echo "   isMark(): " . ($user->role()->isMark() ? 'true' : 'false') . "\n";
    
    // Test password verification
    echo "   Password verification with 'admin123': ";
    echo $user->verifyPassword('admin123') ? 'SUCCESS' : 'FAILED';
    echo "\n";
    
    echo "   Password verification with wrong password: ";
    echo $user->verifyPassword('wrong') ? 'SUCCESS (UNEXPECTED)' : 'FAILED (EXPECTED)';
    echo "\n";
    
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
}

// 4. Test LoginUser use case
echo "\n4. Testing LoginUser use case:\n";
try {
    require_once __DIR__ . '/src/Application/User/LoginUser.php';
    require_once __DIR__ . '/src/Infrastructure/Persistence/Doctrine/DoctrineUserRepository.php';
    
    $userRepo = new \Blog\Infrastructure\Persistence\Doctrine\DoctrineUserRepository($db);
    $loginUser = new \Blog\Application\User\LoginUser($userRepo);
    
    echo "   Attempting login with 'admin@admin.com' and 'admin123':\n";
    $user = $loginUser('admin@admin.com', 'admin123');
    echo "   Login SUCCESS!\n";
    echo "   User ID: " . $user->id()->toString() . "\n";
    echo "   User Role: " . $user->role()->toString() . "\n";
    
    echo "   Attempting login with wrong password:\n";
    try {
        $user = $loginUser('admin@admin.com', 'wrong');
        echo "   Login SUCCESS (UNEXPECTED!)\n";
    } catch (Exception $e) {
        echo "   Login FAILED (EXPECTED): " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== End Test ===\n";