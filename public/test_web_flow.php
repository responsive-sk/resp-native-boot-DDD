<?php
// public/test_web_flow.php
require_once __DIR__ . '/../boot.php';

echo "<pre>";

session_start();

echo "=== Initial State ===\n";
echo "Session ID: " . session_id() . "\n";
echo "Session data: ";
print_r($_SESSION);

echo "\n=== Simulating AuthController::setupUserSession() ===\n";

// Store current session ID
$oldSessionId = session_id();

// This is what causes the issue:
echo "Calling session_regenerate_id(true)...\n";
session_regenerate_id(true);

$newSessionId = session_id();
echo "Old Session ID: $oldSessionId\n";
echo "New Session ID: $newSessionId\n";

// Store user data (like AuthController does)
$_SESSION['user_id'] = 'test-id';
$_SESSION['user_role'] = 'ROLE_MARK';

echo "Session data after: ";
print_r($_SESSION);

// Write and close
session_write_close();

echo "\n=== Simulating New Request ===\n";

// Browser still has OLD session cookie
// Start session with OLD ID
session_id($oldSessionId);
session_start();

echo "Session ID (using old cookie): " . session_id() . "\n";
echo "Session data (should be empty): ";
print_r($_SESSION);

// Now with NEW ID
session_write_close();
session_id($newSessionId);
session_start();

echo "\nSession ID (using new ID): " . session_id() . "\n";
echo "Session data (should have user data): ";
print_r($_SESSION);

echo "\n=== Conclusion ===\n";
echo "If browser sends OLD session ID ($oldSessionId), it gets EMPTY session.\n";
echo "Browser needs to get NEW session ID cookie.\n";

echo "</pre>";