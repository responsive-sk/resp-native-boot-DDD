<?php

declare(strict_types=1);

use App\Database\DatabaseManager;
use App\Infrastructure\Paths;

require \App\Infrastructure\Paths::basePath() . '/vendor/autoload.php';

echo "Starting split: move users table to data/users.db\n";

$usersPath = \App\Infrastructure\Paths::dataPath() . '/users.db';

$appConn = DatabaseManager::getConnection('app');

if (file_exists($usersPath)) {
    echo "users.db already exists at {$usersPath} - aborting to avoid overwrite.\n";
    exit(1);
}

// Attach new DB to current connection and copy the users table
try {
    echo "Attaching new SQLite file and copying users table...\n";
    $attach = sprintf("ATTACH DATABASE '%s' AS users_db", addslashes(realpath(\App\Infrastructure\Paths::dataPath()) . '/users.db'));
    // Since Doctrine/DBAL uses real PDO, use underlying connection
    $pdo = $appConn->getNativeConnection();
    // Create or open the target file by using sqlite3 command via exec is simpler
    // We'll create an empty SQLite file and then use sqlite3 tooling via SQL
    // Fallback: perform manual copy via SELECT INTO on attached DB
    $appConn->executeStatement("ATTACH DATABASE ? AS users_db", [$usersPath]);

    // Create users table in attached DB
    $appConn->executeStatement('CREATE TABLE IF NOT EXISTS users_db.users (id INTEGER PRIMARY KEY AUTOINCREMENT, email VARCHAR(255) UNIQUE NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(50) DEFAULT "ROLE_USER", created_at DATETIME DEFAULT CURRENT_TIMESTAMP)');

    // Copy data
    $rows = $appConn->fetchAllAssociative('SELECT id, email, password, role, created_at FROM users');
    $inserted = 0;
    foreach ($rows as $row) {
        $appConn->insert('users_db.users', $row);
        $inserted++;
    }

    echo "Copied {$inserted} users into data/users.db\n";

    // Optionally, remove users from app DB or leave as-is until switch
    echo "Detached users_db and finishing.\n";
    $appConn->executeStatement('DETACH DATABASE users_db');
} catch (\Throwable $e) {
    echo "Error during split: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Split complete. Review data/users.db and then update app to use it.\n";
