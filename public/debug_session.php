<?php
// debug_session.php - Session debug endpoint
require_once __DIR__ . '/../boot.php';

use Blog\Security\Authorization;

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set content type
header('Content-Type: text/plain; charset=utf-8');

echo "=== Session Debug ===\n\n";

echo "Session ID: " . session_id() . "\n";
echo "Session status: " . session_status() . "\n";
echo "Session name: " . session_name() . "\n";

echo "\nSession data:\n";
if (empty($_SESSION)) {
    echo "No session data\n";
} else {
    foreach ($_SESSION as $key => $value) {
        if (is_string($value)) {
            echo "[$key] = '$value' (length: " . strlen($value) . ")\n";
        } else {
            echo "[$key] = " . json_encode($value) . "\n";
        }
    }
}

echo "\nAuthorization checks:\n";
echo "isAuthenticated(): " . (Authorization::isAuthenticated() ? 'true' : 'false') . "\n";

$user = Authorization::getUser();
echo "getUser(): " . json_encode($user) . "\n";

if ($user) {
    echo "hasRole('ROLE_MARK'): " . (Authorization::hasRole('ROLE_MARK') ? 'true' : 'false') . "\n";
    echo "isMark(): " . (Authorization::isMark() ? 'true' : 'false') . "\n";
    
    echo "\nDebug comparison:\n";
    echo "Session role: '{$user['role']}'\n";
    echo "Expected: 'ROLE_MARK'\n";
    echo "Exact match: " . ($user['role'] === 'ROLE_MARK' ? 'YES' : 'NO') . "\n";
    
    if ($user['role'] !== 'ROLE_MARK') {
        echo "\nCharacter analysis:\n";
        for ($i = 0; $i < max(strlen($user['role']), strlen('ROLE_MARK')); $i++) {
            $char1 = $i < strlen($user['role']) ? $user->role()[$i] : '[END]';
            $char2 = $i < strlen('ROLE_MARK') ? 'ROLE_MARK'[$i] : '[END]';
            $ord1 = $i < strlen($user['role']) ? ord($user['role'][$i]) : 'N/A';
            $ord2 = $i < strlen('ROLE_MARK') ? ord('ROLE_MARK'[$i]) : 'N/A';
            echo "Pos $i: '$char1' ($ord1) vs '$char2' ($ord2) - " . ($char1 === $char2 ? 'MATCH' : 'DIFFERENT') . "\n";
        }
    }
}

echo "\nCookies:\n";
foreach ($_COOKIE as $name => $value) {
    echo "$name = $value\n";
}

echo "\n=== End Debug ===\n";
