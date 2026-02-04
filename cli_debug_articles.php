<?php

require_once __DIR__ . '/vendor/autoload.php';

use Blog\Database\DatabaseManager;

$config = require __DIR__ . '/config/services_ddd.php';

try {
    echo "Connecting to database...\n";
    $conn = DatabaseManager::getConnection();
    $articles = $conn->fetchAllAssociative('SELECT id, title, slug, status FROM articles');

    echo "\n=== ARTICLES IN DB ===\n";
    printf("%-5s | %-40s | %-30s | %s\n", 'ID', 'Title', 'Slug', 'Status');
    echo str_repeat('-', 90) . "\n";

    foreach ($articles as $article) {
        printf(
            "%-5s | %-40s | %-30s | %s\n",
            $article['id'],
            substr($article['title'], 0, 40),
            substr($article['slug'] ?? 'NULL', 0, 30),
            $article['status']
        );
    }
    echo "\nDone.\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
