<?php

declare(strict_types=1);

use Blog\Database\Database;
use Blog\Domain\Blog\ValueObject\Slug;

require \Blog\Infrastructure\Paths::basePath() . '/vendor/autoload.php';

echo "Starting migration: add slug column and backfill posts\n";

$connection = \Blog\Database\DatabaseManager::getConnection('posts');
$schema = $connection->createSchemaManager();

$columns = array_keys($schema->listTableColumns('posts'));

if (!in_array('slug', $columns, true)) {
    echo "Adding 'slug' column to posts table...\n";
    $connection->executeStatement('ALTER TABLE posts ADD COLUMN slug VARCHAR(100) DEFAULT NULL');
} else {
    echo "'slug' column already exists.\n";
}

// Load existing slugs to ensure uniqueness
$existingRows = $connection->fetchAllAssociative('SELECT id, slug FROM posts WHERE slug IS NOT NULL');
$existing = [];
foreach ($existingRows as $r) {
    if (!empty($r['slug'])) {
        $existing[$r['slug']] = true;
    }
}

$rows = $connection->fetchAllAssociative('SELECT id, title FROM posts ORDER BY id ASC');
$updated = 0;

foreach ($rows as $row) {
    $id = (int) $row['id'];
    $title = (string) $row['title'];

    // Skip if slug already set
    $current = $connection->fetchOne('SELECT slug FROM posts WHERE id = ?', [$id]);
    if ($current !== null && $current !== false && $current !== '') {
        continue;
    }

    try {
        $candidate = Slug::fromString($title)->toString();
    } catch (\Throwable $e) {
        $candidate = 'post-' . $id;
    }

    $unique = $candidate;
    $suffix = 1;
    while (isset($existing[$unique])) {
        $unique = $candidate . '-' . $suffix;
        $suffix++;
    }

    $connection->update('posts', ['slug' => $unique], ['id' => $id]);
    $existing[$unique] = true;
    $updated++;
    echo "Backfilled post id={$id} slug={$unique}\n";
}

// Create unique index on slug
echo "Creating unique index on posts.slug (if not exists)...\n";
$connection->executeStatement('CREATE UNIQUE INDEX IF NOT EXISTS idx_posts_slug_unique ON posts(slug)');

echo "Migration complete. Backfilled {$updated} posts.\n";
