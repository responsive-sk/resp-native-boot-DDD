<?php

declare(strict_types=1);

use Blog\Database\DatabaseManager;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

echo "Starting migration: add FTS5 full-text search to articles\n";

$connection = DatabaseManager::getConnection('articles');

try {
    // Check if FTS5 table already exists
    $tables = $connection->fetchFirstColumn("SELECT name FROM sqlite_master WHERE type='table' AND name='articles_fts'");
    
    if (in_array('articles_fts', $tables, true)) {
        echo "FTS5 table 'articles_fts' already exists.\n";
    } else {
        echo "Creating FTS5 virtual table for articles...\n";
        
        // Create FTS5 virtual table
        $connection->executeStatement("
            CREATE VIRTUAL TABLE articles_fts USING fts5(
                title, 
                content, 
                content=articles, 
                content_rowid=id
            )
        ");
        
        echo "FTS5 table created successfully.\n";
    }
    
    // Check if triggers exist
    $triggers = $connection->fetchFirstColumn("SELECT name FROM sqlite_master WHERE type='trigger' AND name LIKE 'articles_fts_%'");
    
    if (count($triggers) >= 3) {
        echo "FTS5 triggers already exist.\n";
    } else {
        echo "Creating FTS5 sync triggers...\n";
        
        // Trigger for INSERT
        $connection->executeStatement("
            CREATE TRIGGER IF NOT EXISTS articles_fts_ai AFTER INSERT ON articles BEGIN
                INSERT INTO articles_fts(rowid, title, content) 
                VALUES (new.id, new.title, new.content);
            END
        ");
        
        // Trigger for DELETE
        $connection->executeStatement("
            CREATE TRIGGER IF NOT EXISTS articles_fts_ad AFTER DELETE ON articles BEGIN
                DELETE FROM articles_fts WHERE rowid = old.id;
            END
        ");
        
        // Trigger for UPDATE
        $connection->executeStatement("
            CREATE TRIGGER IF NOT EXISTS articles_fts_au AFTER UPDATE ON articles BEGIN
                UPDATE articles_fts 
                SET title = new.title, content = new.content 
                WHERE rowid = new.id;
            END
        ");
        
        echo "FTS5 triggers created successfully.\n";
    }
    
    // Backfill existing articles into FTS table
    echo "Backfilling existing articles into FTS table...\n";

    $count = $connection->fetchOne("SELECT COUNT(*) FROM articles");
    echo "Found $count articles to index.\n";

    if ($count > 0) {
        // For external content FTS5 tables, use the 'rebuild' command
        // This is the correct way to populate/rebuild an external content FTS5 index
        $connection->executeStatement("INSERT INTO articles_fts(articles_fts) VALUES('rebuild')");

        echo "Backfilled $count articles into FTS index.\n";
    }
    
    echo "Migration complete! Full-text search is now enabled.\n";
    echo "You can now search articles using: SELECT * FROM articles WHERE id IN (SELECT rowid FROM articles_fts WHERE articles_fts MATCH 'query')\n";
    
} catch (\Throwable $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

