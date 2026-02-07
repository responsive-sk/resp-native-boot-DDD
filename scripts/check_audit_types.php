<?php

declare(strict_types=1);

require_once __DIR__ . '/../boot.php';

use Blog\Domain\Audit\ValueObject\AuditEventType;

echo "=== Audit Event Types Check ===\n\n";

// 1. Získaj všetky typy
$allTypes = AuditEventType::getAllTypes();

echo "Available event types:\n";
foreach ($allTypes as $type => $description) {
    echo "  - $type: $description\n";
}

// 2. Test vytvorenie
echo "\nTesting VO creation:\n";

$testTypes = [
    AuditEventType::LOGIN_SUCCESS,
    AuditEventType::AUTHORIZATION_DENIED,
    AuditEventType::ARTICLE_CREATED,
];

foreach ($testTypes as $type) {
    try {
        $vo = new AuditEventType($type);
        echo "✓ $type: " . $vo->value() . "\n";
    } catch (\InvalidArgumentException $e) {
        echo "✗ $type: " . $e->getMessage() . "\n";
    }
}

// 3. Test invalid type
echo "\nTesting invalid type:\n";
try {
    $invalid = new AuditEventType('invalid_event_type');
    echo "✗ Should have thrown exception!\n";
} catch (\InvalidArgumentException $e) {
    echo "✓ Correctly rejected invalid type: " . $e->getMessage() . "\n";
}

echo "\n✅ Audit Event Types are properly implemented.\n";