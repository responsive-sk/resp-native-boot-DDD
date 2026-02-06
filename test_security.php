<?php

declare(strict_types=1);

// Simple security test script
require_once __DIR__ . '/boot.php';

use Blog\Security\Authorization;

// Initialize session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "=== SECURITY TEST ===\n\n";

// Test 1: Unauthenticated access
echo "1. Testing unauthenticated access:\n";
$user = Authorization::getUser();
echo "   Current user: " . ($user ? $user['id'] : 'null') . "\n";
echo "   Is authenticated: " . (Authorization::isAuthenticated() ? 'true' : 'false') . "\n\n";

// Test 2: Simulate logged in user
echo "2. Simulating logged in user:\n";
$_SESSION['user_id'] = 'test-user-123';
$_SESSION['user_role'] = 'user';

$user = Authorization::getUser();
echo "   Current user: " . ($user ? $user['id'] : 'null') . "\n";
echo "   User role: " . ($user ? $user['role'] : 'null') . "\n";
echo "   Is authenticated: " . (Authorization::isAuthenticated() ? 'true' : 'false') . "\n\n";

// Test 3: Check authentication requirement
echo "3. Testing authentication requirement:\n";
try {
    Authorization::requireAuth();
    echo "   ✅ Authentication check passed\n";
} catch (\Blog\Security\Exception\AuthenticationException $e) {
    echo "   ❌ Authentication failed: " . $e->getMessage() . "\n";
}

echo "\n=== SECURITY TEST COMPLETE ===\n";
