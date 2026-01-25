<?php

declare(strict_types=1);

use App\Database\Database;

$require = require \App\Infrastructure\Paths::basePath() . '/vendor/autoload.php';

echo "Starting rollback: remove slug column and index from posts\n";

$connection = \App\Database\DatabaseManager::getConnection('posts');
$platform = $connection->getDatabasePlatform()->getName();

echo "Detected platform: {$platform}\n";

try {
    // Drop the unique index (platform-specific syntax differences handled conservatively)
    echo "Dropping index idx_posts_slug_unique if it exists...\n";
    try {
        if ($platform === 'mysql') {
            // MySQL DROP INDEX requires ON <table>
            $connection->executeStatement('DROP INDEX idx_posts_slug_unique ON posts');
        } else {
            // SQLite / PostgreSQL support DROP INDEX IF EXISTS
            $connection->executeStatement('DROP INDEX IF EXISTS idx_posts_slug_unique');
        }
        echo "Index drop attempted.\n";
    } catch (\Throwable $e) {
        echo "Index drop failed or not present: " . $e->getMessage() . "\n";
    }

    // Attempt to drop the column
    echo "Attempting to remove 'slug' column from posts...\n";
    try {
        if (in_array($platform, ['mysql', 'postgresql'], true)) {
            $connection->executeStatement('ALTER TABLE posts DROP COLUMN slug');
            echo "Dropped 'slug' column on {$platform}.\n";
        } elseif ($platform === 'sqlite') {
            // SQLite may not support DROP COLUMN depending on version.
            try {
                $connection->executeStatement('ALTER TABLE posts DROP COLUMN slug');
                echo "Dropped 'slug' column on sqlite.\n";
            } catch (\Throwable $e) {
                echo "SQLite does not support DROP COLUMN on this version. Manual table rebuild is required to remove a column.\n";
                echo "To rollback on SQLite, rebuild the 'posts' table without the 'slug' column and re-create indexes.\n";
            }
        } else {
            echo "Unknown platform '{$platform}' — please remove 'slug' column and index manually.\n";
        }
    } catch (\Throwable $e) {
        echo "Error removing column: " . $e->getMessage() . "\n";
    }

    echo "Rollback script finished. Verify your schema and indices after running.\n";
} catch (\Throwable $e) {
    echo "Unexpected error during rollback: " . $e->getMessage() . "\n";
    exit(1);
}
