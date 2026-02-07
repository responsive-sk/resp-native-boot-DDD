<?php

// migrations/run_migrations.php

declare(strict_types=1);

require_once __DIR__ . '/../../boot.php';

use Blog\Database\DatabaseManager;

class MigrationRunner
{
    private array $migrations = [];

    public function __construct()
    {
        $migrationsDir = __DIR__;
        $databaseDirs = array_filter(glob($migrationsDir . '/*'), 'is_dir');

        foreach ($databaseDirs as $dbPath) {
            $dbName = basename($dbPath);
            $sqlFiles = glob($dbPath . '/*.sql');
            sort($sqlFiles); // Ensure migrations run in order

            foreach ($sqlFiles as $sqlFile) {
                $filename = basename($sqlFile);
                // Generate a description from the filename (e.g., '001_audit_logs.sql' -> 'audit logs')
                $description = str_replace(['.sql', '_'], ['',' '], preg_replace('/^\d+_/', '', $filename));
                $this->migrations[$dbName][$filename] = ucfirst($description);
            }
        }
    }

    public function run(bool $force = false): void
    {
        echo "=== DDD Database Migration Runner ===\n\n";

        foreach ($this->migrations as $database => $files) {
            $this->migrateDatabase($database, $files, $force);
        }

        echo "\n=== Migration Complete ===\n";
    }

    private function migrateDatabase(string $database, array $migrations, bool $force): void
    {
        echo "Database: $database\n";
        echo str_repeat('-', 50) . "\n";

        try {
            $conn = DatabaseManager::getConnection($database);

            // Check if migrations table exists
            $migrationsTable = "{$database}_migrations";
            $this->ensureMigrationsTable($conn, $migrationsTable);

            foreach ($migrations as $file => $description) {
                $this->runMigration($conn, $database, $file, $description, $migrationsTable, $force);
            }

        } catch (Exception $e) {
            echo "  ✗ Error: " . $e->getMessage() . "\n";
            throw $e;
        }

        echo "\n";
    }

    private function ensureMigrationsTable(Doctrine\DBAL\Connection $conn, string $tableName): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS $tableName (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            migration TEXT NOT NULL UNIQUE,
            batch INTEGER NOT NULL,
            executed_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";

        $conn->executeStatement($sql);
    }

    private function runMigration(
        Doctrine\DBAL\Connection $conn,
        string $database,
        string $file,
        string $description,
        string $migrationsTable,
        bool $force
    ): void {
        $path = __DIR__ . "/$database/$file";

        if (!file_exists($path)) {
            echo "  ⚠ Migration file not found: $file\n";

            return;
        }

        // Check if already migrated
        $sql = "SELECT migration FROM $migrationsTable WHERE migration = ?";
        $alreadyMigrated = $conn->fetchOne($sql, [$file]);

        if ($alreadyMigrated && !$force) {
            echo "  ✓ Already migrated: $file\n";

            return;
        }

        echo "  → Running: $file ($description)... ";

        try {
            // Start transaction
            $conn->beginTransaction();

            // Read and execute SQL
            $sqlContent = file_get_contents($path);
            $conn->executeStatement($sqlContent);

            // Record migration
            if ($alreadyMigrated && $force) {
                $sql = "UPDATE $migrationsTable SET executed_at = CURRENT_TIMESTAMP WHERE migration = ?";
                $conn->executeStatement($sql, [$file]);
            } else {
                $batch = $this->getNextBatch($conn, $migrationsTable);
                $sql = "INSERT INTO $migrationsTable (migration, batch) VALUES (?, ?)";
                $conn->executeStatement($sql, [$file, $batch]);
            }

            $conn->commit();
            echo "✓ Done\n";

        } catch (Exception $e) {
            $conn->rollBack();
            echo "✗ Failed: " . $e->getMessage() . "\n";

            throw $e;
        }
    }

    private function getNextBatch(Doctrine\DBAL\Connection $conn, string $tableName): int
    {
        $sql = "SELECT MAX(batch) as max_batch FROM $tableName";
        $result = $conn->fetchOne($sql);

        return ($result ?: 0) + 1;
    }
}


