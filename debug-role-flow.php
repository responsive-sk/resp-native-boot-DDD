<?php
// debug_role_flow.php
require_once __DIR__ . '/boot.php';

use Blog\Domain\User\ValueObject\UserRole;

echo "=== Testing UserRole Value Object ===\n\n";

// Test 1: Create a mark role
try {
    $markRole = UserRole::mark();
    echo "1. UserRole::mark() created:\n";
    echo "   toString(): " . $markRole->toString() . "\n";
    echo "   __toString(): " . (string)$markRole . "\n";
    echo "   isMark(): " . ($markRole->isMark() ? 'true' : 'false') . "\n";
    echo "   Type: " . gettype($markRole->toString()) . "\n";
    echo "   Length: " . strlen($markRole->toString()) . "\n";
    echo "   Hex: " . bin2hex($markRole->toString()) . "\n";
} catch (Exception $e) {
    echo "Error creating mark role: " . $e->getMessage() . "\n";
}

echo "\n2. Testing fromString():\n";
try {
    $roleFromString = UserRole::fromString('ROLE_MARK');
    echo "   UserRole::fromString('ROLE_MARK'): " . $roleFromString->toString() . "\n";
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

// Test with lowercase
echo "\n3. Testing invalid role:\n";
try {
    $invalidRole = UserRole::fromString('mark');
    echo "   UserRole::fromString('mark'): " . $invalidRole->toString() . "\n";
} catch (Exception $e) {
    echo "   Expected error: " . $e->getMessage() . "\n";
}

echo "\n=== End Test ===\n";