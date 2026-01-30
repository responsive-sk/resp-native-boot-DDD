<?php

declare(strict_types=1);

use Blog\Database\DatabaseManager;

require \Blog\Infrastructure\Paths::basePath() . '/vendor/autoload.php';

echo "Starting split: move posts table to data/posts.db\n";

$appConn = DatabaseManager::getConnection('app');
$postsPath = \Blog\Infrastructure\Paths::dataPath() . '/posts.db';

if (file_exists($postsPath)) {
    echo "posts.db already exists at {$postsPath} - aborting to avoid overwrite.\n";
    exit(1);
}

try {
    echo "Attaching new SQLite file and copying posts table...\n";
    $appConn->executeStatement('ATTACH DATABASE ? AS posts_db', [$postsPath]);

    $appConn->executeStatement('CREATE TABLE IF NOT EXISTS posts_db.posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        user_id INTEGER,
        status VARCHAR(50) DEFAULT "draft",
        slug VARCHAR(100) DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )');

    $rows = $appConn->fetchAllAssociative('SELECT id, title, content, user_id, status, slug, created_at, updated_at FROM posts');
    $inserted = 0;
    foreach ($rows as $row) {
        $appConn->insert('posts_db.posts', $row);
        $inserted++;
    }

    echo "Copied {$inserted} posts into data/posts.db\n";

    $appConn->executeStatement('DETACH DATABASE posts_db');
} catch (\Throwable $e) {
    echo "Error during split: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Split complete. Review data/posts.db and then update app to use it.\n";
