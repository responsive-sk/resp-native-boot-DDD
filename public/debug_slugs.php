<?php
require_once __DIR__ . '/vendor/autoload.php';

use Blog\Database\DatabaseManager;

$config = require __DIR__ . '/config/services_ddd.php';

// Manually get connection (bypassing container for quick script)
$dbPath = __DIR__ . '/var/database.sqlite'; // Assuming SQLite based on previous context, or check DatabaseManager
// Actually, let's use the DatabaseManager directly if possible or checking the service config.
// The service config uses DatabaseManager::getConnection().

try {
    $conn = DatabaseManager::getConnection();
    $articles = $conn->fetchAllAssociative('SELECT id, title, slug FROM articles');

    echo "<h1>Articles in Database</h1>";
    echo "<table border='1'><tr><th>ID</th><th>Title</th><th>Slug</th></tr>";
    foreach ($articles as $article) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars((string) $article['id']) . "</td>";
        echo "<td>" . htmlspecialchars((string) $article['title']) . "</td>";
        echo "<td>" . htmlspecialchars((string) $article['slug']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
