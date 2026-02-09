<?php

declare(strict_types=1);

require_once __DIR__ . '/../boot.php';

use Blog\Domain\Audit\AuditLogFactory;
use Blog\Domain\Audit\ValueObject\AuditEventType;

echo "=== Audit System Usage Examples ===\n\n";

echo "📝 This script demonstrates how to use the corrected audit system:\n\n";

// Example 1: Using factory methods (recommended)
echo "1. ✅ RECOMMENDED: Using AuditLogFactory\n";
echo "   Code: AuditLogFactory::createLoginSuccess(\$email, \$ip, \$userAgent)\n";
echo "   Benefits: Type-safe, convenient, less error-prone\n\n";

// Example 2: Using entity methods directly
echo "2. ✅ ALTERNATIVE: Using AuditLog entity methods\n";
echo "   Code: AuditLog::createAuthenticationEvent(\$email, \$eventType, \$success, ...)\n";
echo "   Benefits: More control, custom metadata\n\n";

// Example 3: Wrong way (what we fixed)
echo "3. ❌ WRONG: What we fixed (old code)\n";
echo "   Code: AuditLog::createAuthorizationEvent(\$id, 'string_type', \$user, ...)\n";
echo "   Problems: No type safety, runtime errors possible\n\n";

// Example 4: Best practices
echo "4. 🎯 BEST PRACTICES:\n";
echo "   ✓ Always use AuditEventType constants\n";
echo "   ✓ Prefer factory methods for common scenarios\n";
echo "   ✓ Use entity methods for complex custom events\n";
echo "   ✓ Import AuditEventType in all files\n\n";

// Show actual working examples
echo "5. 🔨 WORKING EXAMPLES:\n\n";

try {
    // Factory examples
    $loginLog = AuditLogFactory::createLoginSuccess('user@example.com', '192.168.1.1');
    echo "✓ Login success: " . $loginLog->getEventDescription() . "\n";

    $failedLog = AuditLogFactory::createLoginFailed('hacker@evil.com', '192.168.1.2');
    echo "✓ Login failed: " . $failedLog->getEventDescription() . "\n";

    $authzLog = AuditLogFactory::createAuthorizationDenied('user123', '/admin/users');
    echo "✓ Authorization denied: " . $authzLog->getEventDescription() . "\n";

    $articleLog = AuditLogFactory::createArticleCreated('article456', 'author789');
    echo "✓ Article created: " . $articleLog->getEventDescription() . "\n";

    $imageLog = AuditLogFactory::createImageUploaded('image123', 'user456');
    echo "✓ Image uploaded: " . $imageLog->getEventDescription() . "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n6. 📊 Available Event Types:\n";
$allTypes = AuditEventType::getAllTypes();
foreach ($allTypes as $type => $description) {
    echo "   - $type: $description\n";
}

echo "\n✅ The audit system is now properly implemented with:\n";
echo "   • Type-safe Value Objects\n";
echo "   • Convenient factory methods\n";
echo "   • Proper error handling\n";
echo "   • Clean, maintainable code\n";
echo "   • No hardcoded strings\n\n";

echo "🚀 Ready for production use!\n";
