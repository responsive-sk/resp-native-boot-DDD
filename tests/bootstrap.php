<?php
declare(strict_types=1);

// Ensure autoload is available
require __DIR__ . '/../vendor/autoload.php';

// Ensure per-model DB files are initialized for tests
use Blog\Database\DatabaseManager;

// Create users and posts DBs (DatabaseManager will create tables if missing)
DatabaseManager::getConnection('users');
DatabaseManager::getConnection('posts');

// Optionally set writable paths for tests
@mkdir(base_path('var'), 0777, true);

echo "PHPUnit bootstrap: per-model DBs initialized\n";
