<?php

declare(strict_types=1);

require_once __DIR__ . '/../boot.php';

use Blog\Database\DatabaseManager;

echo "=== Migrating Audit Logs Data ===\n\n";

try {
    $conn = DatabaseManager::getConnection('app');

    // 1. Check if we have old audit_logs table
    $tables = $conn->fetchFirstColumn("
        SELECT name FROM sqlite_master 
        WHERE type='table' AND name='audit_logs'
    ");

    if (empty($tables)) {
        echo "No audit_logs table found.\n";
        exit(0);
    }

    // 2. Check columns
    $columns = $conn->fetchAllAssociative("PRAGMA table_info(audit_logs)");
    $columnNames = array_column($columns, 'name');

    echo "Current columns: " . implode(', ', $columnNames) . "\n";

    if (in_array('description', $columnNames)) {
        echo "⚠ Found 'description' column. Migrating data...\n";

        // 3. Create backup
        $conn->executeStatement("CREATE TABLE IF NOT EXISTS audit_logs_backup AS SELECT * FROM audit_logs");
        echo "✓ Backup created.\n";

        // 4. Create new table without description
        $conn->executeStatement("DROP TABLE IF EXISTS audit_logs_new");
        $conn->executeStatement("
            CREATE TABLE audit_logs_new (
                id TEXT PRIMARY KEY,
                user_id TEXT NULL,
                event_type TEXT NOT NULL,
                ip_address TEXT NULL,
                user_agent TEXT NULL,
                metadata TEXT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
            )
        ");

        // 5. Copy data (excluding description)
        echo "Migrating records...\n";
        $conn->executeStatement("
            INSERT INTO audit_logs_new (id, user_id, event_type, ip_address, user_agent, metadata, created_at)
            SELECT id, user_id, event_type, ip_address, user_agent, metadata, created_at
            FROM audit_logs
        ");

        $migrated = $conn->fetchOne("SELECT COUNT(*) FROM audit_logs_new");
        echo "✓ Migrated {$migrated} records.\n";

        // 6. Swap tables
        $conn->executeStatement("DROP TABLE audit_logs");
        $conn->executeStatement("ALTER TABLE audit_logs_new RENAME TO audit_logs");

        // 7. Create indexes
        $indexes = [
            "idx_audit_logs_user_id" => "CREATE INDEX idx_audit_logs_user_id ON audit_logs(user_id)",
            "idx_audit_logs_event_type" => "CREATE INDEX idx_audit_logs_event_type ON audit_logs(event_type)",
            "idx_audit_logs_created_at" => "CREATE INDEX idx_audit_logs_created_at ON audit_logs(created_at)",
        ];

        foreach ($indexes as $name => $sql) {
            $conn->executeStatement($sql);
            echo "✓ Created index: {$name}\n";
        }

        // 8. Test
        echo "\nTesting new structure...\n";
        $testInsert = [
            'id' => 'test-' . uniqid(),
            'user_id' => null,
            'event_type' => 'migration_test',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Migration Script',
            'metadata' => json_encode(['migration' => true]),
        ];

        $conn->insert('audit_logs', $testInsert);

        $count = $conn->fetchOne("SELECT COUNT(*) FROM audit_logs");
        echo "✓ Total records after migration: {$count}\n";

        // 9. Cleanup
        $conn->executeStatement("DROP TABLE IF EXISTS audit_logs_backup");

        echo "\n✅ Migration complete!\n";

    } else {
        echo "✅ Table structure is already correct.\n";
    }

} catch (\Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";

    // Try to restore from backup
    try {
        if ($conn->fetchOne("SELECT COUNT(*) FROM sqlite_master WHERE type='table' AND name='audit_logs_backup'")) {
            $conn->executeStatement("DROP TABLE IF EXISTS audit_logs");
            $conn->executeStatement("ALTER TABLE audit_logs_backup RENAME TO audit_logs");
            echo "\n⚠ Restored from backup.\n";
        }
    } catch (\Exception $restoreError) {
        echo "⚠ Could not restore: " . $restoreError->getMessage() . "\n";
    }

    exit(1);
}
