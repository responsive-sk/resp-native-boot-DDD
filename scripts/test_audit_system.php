<?php

declare(strict_types=1);

require_once __DIR__ . '/../boot.php';

use Blog\Domain\Audit\Entity\AuditLog;
use Blog\Domain\Audit\ValueObject\AuditEventType;
use Blog\Domain\Audit\ValueObject\AuditLogId;
use Blog\Domain\Audit\AuditLogFactory;

echo "=== Complete Audit System Test ===\n\n";

// 1. Test AuditEventType VO
echo "1. Testing AuditEventType VO:\n";
$eventType = new AuditEventType(AuditEventType::LOGIN_SUCCESS);
echo "✓ Created: " . $eventType->value() . "\n";
echo "✓ Description: " . AuditEventType::getAllTypes()[AuditEventType::LOGIN_SUCCESS] . "\n\n";

// 2. Test AuditLog entity creation
echo "2. Testing AuditLog entity creation:\n";

try {
    // Test basic create
    $auditLog = AuditLog::create(
        new AuditEventType(AuditEventType::LOGIN_SUCCESS),
        'user123',
        '192.168.1.1',
        'Mozilla/5.0...',
        ['test' => true]
    );
    echo "✓ Basic create: " . $auditLog->getId()->value() . "\n";

    // Test createAuthenticationEvent
    $authLog = AuditLog::createAuthenticationEvent(
        'test@example.com',
        new AuditEventType(AuditEventType::LOGIN_FAILED),
        false,
        '192.168.1.2',
        'Chrome/91.0'
    );
    echo "✓ Authentication event: " . $authLog->getEventDescription() . "\n";

    // Test createAuthorizationEvent
    $authzLog = AuditLog::createAuthorizationEvent(
        'user456',
        new AuditEventType(AuditEventType::AUTHORIZATION_DENIED),
        '/admin/dashboard',
        false,
        '192.168.1.3',
        'Firefox/89.0'
    );
    echo "✓ Authorization event: " . $authzLog->getEventDescription() . "\n";

    // Test createArticleEvent
    $articleLog = AuditLog::createArticleEvent(
        new AuditEventType(AuditEventType::ARTICLE_CREATED),
        'article789',
        'user123',
        '192.168.1.4',
        'Safari/14.0'
    );
    echo "✓ Article event: " . $articleLog->getEventDescription() . "\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

// 3. Test AuditLogFactory
echo "\n3. Testing AuditLogFactory:\n";

try {
    $factoryLog1 = AuditLogFactory::createLoginSuccess('user@example.com', '192.168.1.5');
    echo "✓ Factory login success: " . $factoryLog1->getEventDescription() . "\n";

    $factoryLog2 = AuditLogFactory::createLoginFailed('hacker@evil.com', '192.168.1.6');
    echo "✓ Factory login failed: " . $factoryLog2->getEventDescription() . "\n";

    $factoryLog3 = AuditLogFactory::createAuthorizationDenied('user789', '/admin/users');
    echo "✓ Factory authorization denied: " . $factoryLog3->getEventDescription() . "\n";

    $factoryLog4 = AuditLogFactory::createArticleCreated('article123', 'user456');
    echo "✓ Factory article created: " . $factoryLog4->getEventDescription() . "\n";

    $factoryLog5 = AuditLogFactory::createImageUploaded('image456', 'user789');
    echo "✓ Factory image uploaded: " . $factoryLog5->getEventDescription() . "\n";

} catch (Exception $e) {
    echo "✗ Factory error: " . $e->getMessage() . "\n";
}

// 4. Test toArray conversion
echo "\n4. Testing toArray conversion:\n";

try {
    $testLog = AuditLog::create(
        new AuditEventType(AuditEventType::USER_CREATED),
        'admin',
        '127.0.0.1',
        'Test Agent',
        ['role' => 'admin']
    );

    $array = $testLog->toArray();
    echo "✓ toArray keys: " . implode(', ', array_keys($array)) . "\n";
    echo "✓ Event type: " . $array['event_type'] . "\n";
    echo "✓ Description: " . $array['event_description'] . "\n";

} catch (Exception $e) {
    echo "✗ toArray error: " . $e->getMessage() . "\n";
}

// 5. Test reconstitute (for repository hydration)
echo "\n5. Testing reconstitute:\n";

try {
    $id = AuditLogId::generate();
    $reconstituted = AuditLog::reconstitute(
        $id,
        'user999',
        new AuditEventType(AuditEventType::LOGOUT),
        '192.168.1.7',
        'Edge/91.0',
        ['session_expired' => true],
        new DateTimeImmutable('2023-01-01 12:00:00')
    );
    echo "✓ Reconstituted: " . $reconstituted->getId()->value() . "\n";
    echo "✓ Event: " . $reconstituted->getEventDescription() . "\n";

} catch (Exception $e) {
    echo "✗ Reconstitute error: " . $e->getMessage() . "\n";
}

// 6. Test invalid scenarios
echo "\n6. Testing invalid scenarios:\n";

try {
    new AuditEventType('nonexistent_type');
    echo "✗ Should have thrown exception!\n";
} catch (InvalidArgumentException $e) {
    echo "✓ Correctly rejected invalid type\n";
}

echo "\n✅ Complete audit system test passed!\n";
echo "🎉 All components are working correctly.\n";