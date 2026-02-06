<?php
// convert_users_to_blob.php
$usersDb = new SQLite3('data/users.db');
$articlesDb = new SQLite3('data/articles.db');

// 1. Get all current users
$result = $usersDb->query('SELECT * FROM users');
$users = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $users[] = $row;
}

echo "Found " . count($users) . " users\n";

// 2. Create new table with BLOB id
$usersDb->exec('DROP TABLE IF EXISTS users_new');
$usersDb->exec('
    CREATE TABLE users_new (
        id BLOB PRIMARY KEY,
        email VARCHAR(255) UNIQUE NOT NULL,
        password_hash TEXT NOT NULL,
        role VARCHAR(50) DEFAULT "ROLE_USER",
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
');

// 3. Convert each user
foreach ($users as $user) {
    $id = $user['id'];
    
    // If it's a hex string (32 chars), convert to BLOB
    if (strlen($id) === 32 && ctype_xdigit($id)) {
        $idBlob = hex2bin($id);
    } 
    // If it's already BLOB (16 bytes)
    elseif (strlen($id) === 16) {
        $idBlob = $id;
    }
    // Otherwise generate new UUID
    else {
        $idBlob = random_bytes(16);
        $idBlob[6] = chr(ord($idBlob[6]) & 0x0f | 0x40);
        $idBlob[8] = chr(ord($idBlob[8]) & 0x3f | 0x80);
    }
    
    // Insert into new table
    $stmt = $usersDb->prepare('
        INSERT INTO users_new (id, email, password_hash, role, created_at) 
        VALUES (:id, :email, :hash, :role, :created_at)
    ');
    $stmt->bindValue(':id', $idBlob, SQLITE3_BLOB);
    $stmt->bindValue(':email', $user['email']);
    $stmt->bindValue(':hash', $user['password_hash'] ?? '');
    $stmt->bindValue(':role', $user['role'] ?? 'ROLE_USER');
    $stmt->bindValue(':created_at', $user['created_at'] ?? date('Y-m-d H:i:s'));
    $stmt->execute();
    
    echo "Converted user: " . $user['email'] . "\n";
}

// 4. Replace old table
$usersDb->exec('DROP TABLE users');
$usersDb->exec('ALTER TABLE users_new RENAME TO users');

// 5. Add default admin if not exists
$adminUuid = random_bytes(16);
$adminUuid[6] = chr(ord($adminUuid[6]) & 0x0f | 0x40);
$adminUuid[8] = chr(ord($adminUuid[8]) & 0x3f | 0x80);

$stmt = $usersDb->prepare('
    INSERT OR IGNORE INTO users (id, email, password_hash, role) 
    VALUES (:id, :email, :hash, :role)
');
$stmt->bindValue(':id', $adminUuid, SQLITE3_BLOB);
$stmt->bindValue(':email', 'admin@admin.com');
$stmt->bindValue(':hash', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); // password
$stmt->bindValue(':role', 'ROLE_MARK');
$stmt->execute();

echo "✅ Users converted to BLOB successfully!\n";
echo "Admin credentials: admin@admin.com / admin123\n";

// 6. Now fix articles table
$userResult = $usersDb->query('SELECT id FROM users LIMIT 1');
$firstUserId = $userResult->fetchArray(SQLITE3_ASSOC)['id'];

if ($firstUserId) {
    // Update articles.user_id to BLOB
    $articlesDb->exec('DROP TABLE IF EXISTS articles_new');
    $articlesDb->exec('
        CREATE TABLE articles_new (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(100) UNIQUE,
            content TEXT NOT NULL,
            user_id BLOB NOT NULL,
            status VARCHAR(50) DEFAULT "draft",
            category_id VARCHAR(36),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ');
    
    // Copy existing articles with BLOB user_id
    $articlesResult = $articlesDb->query('SELECT * FROM articles');
    while ($article = $articlesResult->fetchArray(SQLITE3_ASSOC)) {
        $stmt = $articlesDb->prepare('
            INSERT INTO articles_new 
            (id, title, slug, content, user_id, status, category_id, created_at, updated_at)
            VALUES (:id, :title, :slug, :content, :user_id, :status, :category_id, :created_at, :updated_at)
        ');
        
        foreach ($article as $key => $value) {
            if ($key === 'user_id') {
                $stmt->bindValue(':user_id', $firstUserId, SQLITE3_BLOB);
            } else {
                $stmt->bindValue(":$key", $value);
            }
        }
        $stmt->execute();
    }
    
    $articlesDb->exec('DROP TABLE articles');
    $articlesDb->exec('ALTER TABLE articles_new RENAME TO articles');
    
    // Add test articles
    $testArticles = [
        ['Test Article 1', 'test-article-1', 'Content of first article', 'published'],
        ['Test Article 2', 'test-article-2', 'Content of second article', 'draft'],
        ['DDD Introduction', 'ddd-intro', 'Domain Driven Design basics', 'published']
    ];
    
    foreach ($testArticles as $article) {
        $stmt = $articlesDb->prepare('
            INSERT INTO articles (title, slug, content, user_id, status)
            VALUES (:title, :slug, :content, :user_id, :status)
        ');
        $stmt->bindValue(':title', $article[0]);
        $stmt->bindValue(':slug', $article[1]);
        $stmt->bindValue(':content', $article[2]);
        $stmt->bindValue(':user_id', $firstUserId, SQLITE3_BLOB);
        $stmt->bindValue(':status', $article[3]);
        $stmt->execute();
    }
    
    echo "✅ Articles table updated with BLOB user_id\n";
    echo "✅ Added " . count($testArticles) . " test articles\n";
}

echo "\n=== FINAL CHECK ===";
echo "\nUsers count: " . $usersDb->querySingle('SELECT COUNT(*) FROM users');
echo "\nArticles count: " . $articlesDb->querySingle('SELECT COUNT(*) FROM articles');
echo "\n\n🎉 Database conversion complete!\n";
?>