<?php
/**
 * Migration: Convert user_id columns from INTEGER to BLOB (UUID)
 * 
 * This script:
 * 1. Alters the users table to use BLOB for id
 * 2. Alters the articles table to use BLOB for user_id
 * 3. Migrates existing data (converts integer IDs to UUIDs)
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Ramsey\Uuid\Uuid;

$articlesDbPath = __DIR__ . '/../data/articles.db';
$usersDbPath = __DIR__ . '/../data/users.db';

echo "🚀 Starting UUID migration...\n\n";

// ===== STEP 1: Migrate Users Table =====
echo "📦 Step 1: Migrating users table...\n";

$usersDb = new PDO('sqlite:' . $usersDbPath);
$usersDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get existing users with INTEGER IDs
$existingUsers = $usersDb->query("SELECT id, email, password, role, created_at FROM users")->fetchAll(PDO::FETCH_ASSOC);

echo "   Found " . count($existingUsers) . " existing users\n";

// Create new table with BLOB id
$usersDb->exec("
    CREATE TABLE users_new (
        id BLOB PRIMARY KEY,
        email TEXT UNIQUE NOT NULL,
        password_hash TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'ROLE_USER',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

// Mapping of old integer IDs to new UUIDs
$userIdMap = [];

// Insert users with new UUIDs
foreach ($existingUsers as $user) {
    $oldId = $user['id'];
    $uuid = Uuid::uuid4();
    $uuidBytes = $uuid->getBytes();

    $userIdMap[$oldId] = $uuidBytes; // Store mapping

    $stmt = $usersDb->prepare("
        INSERT INTO users_new (id, email, password_hash, role, created_at)
        VALUES (:id, :email, :password, :role, :created_at)
    ");

    $stmt->execute([
        ':id' => $uuidBytes,
        ':email' => $user['email'],
        ':password' => $user['password'],
        ':role' => $user['role'],
        ':created_at' => $user['created_at']
    ]);

    echo "   ✓ Migrated user: {$user['email']} (ID: {$oldId} → UUID: {$uuid->toString()})\n";
}

// Drop old table and rename new one
$usersDb->exec("DROP TABLE users");
$usersDb->exec("ALTER TABLE users_new RENAME TO users");

echo "   ✅ Users table migrated successfully\n\n";

// ===== STEP 2: Migrate Articles Table =====
echo "📦 Step 2: Migrating articles table...\n";

$articlesDb = new PDO('sqlite:' . $articlesDbPath);
$articlesDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get existing articles
$existingArticles = $articlesDb->query("SELECT * FROM articles")->fetchAll(PDO::FETCH_ASSOC);

echo "   Found " . count($existingArticles) . " existing articles\n";

// Create new table with BLOB user_id
$articlesDb->exec("
    CREATE TABLE articles_new (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        user_id BLOB,
        status VARCHAR(50) DEFAULT 'draft',
        slug VARCHAR(100) DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        category TEXT
    )
");

// Insert articles with migrated user_ids
foreach ($existingArticles as $article) {
    $oldUserId = $article['user_id'];
    $newUserId = $userIdMap[$oldUserId] ?? null;

    if ($newUserId === null) {
        echo "   ⚠️  Warning: User ID {$oldUserId} not found in mapping, skipping article: {$article['title']}\n";
        continue;
    }

    $stmt = $articlesDb->prepare("
        INSERT INTO articles_new (id, title, content, user_id, status, slug, created_at, updated_at, category)
        VALUES (:id, :title, :content, :user_id, :status, :slug, :created_at, :updated_at, :category)
    ");

    $stmt->execute([
        ':id' => $article['id'],
        ':title' => $article['title'],
        ':content' => $article['content'],
        ':user_id' => $newUserId,
        ':status' => $article['status'],
        ':slug' => $article['slug'],
        ':created_at' => $article['created_at'],
        ':updated_at' => $article['updated_at'],
        ':category' => $article['category']
    ]);

    echo "   ✓ Migrated article: {$article['title']} (user_id: {$oldUserId} → UUID)\n";
}

// Drop old table and rename new one
$articlesDb->exec("DROP TABLE articles");
$articlesDb->exec("ALTER TABLE articles_new RENAME TO articles");

// Recreate FTS table if it exists
try {
    $articlesDb->exec("DROP TABLE IF EXISTS articles_fts");
    $articlesDb->exec("
        CREATE VIRTUAL TABLE articles_fts USING fts5(title, content, content='articles', content_rowid='id')
    ");
    $articlesDb->exec("
        INSERT INTO articles_fts(rowid, title, content)
        SELECT id, title, content FROM articles
    ");
    echo "   ✅ FTS index rebuilt\n";
} catch (Exception $e) {
    echo "   ⚠️  FTS index not rebuilt: " . $e->getMessage() . "\n";
}

echo "   ✅ Articles table migrated successfully\n\n";

echo "✅ Migration completed successfully!\n";
echo "   Users: " . count($existingUsers) . " migrated\n";
echo "   Articles: " . count($existingArticles) . " migrated\n";
