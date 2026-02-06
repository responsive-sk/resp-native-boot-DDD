<?php
// public/web_debug.php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

echo "<h1>DDD App - Ultimate Debug</h1>";

try {
    // 1. Autoload
    require_once __DIR__ . '/../vendor/autoload.php';
    echo "<p>✅ Autoload loaded</p>";
    
    // 2. Container
    $containerFactory = require __DIR__ . '/../config/container.php';
    $container = $containerFactory();
    echo "<p>✅ Container created</p>";
    
    // 3. Database
    $db = $container->get('database');
    echo "<p>✅ Database: " . get_class($db) . "</p>";
    
    // 4. Test query
    $articles = $db->fetchAllAssociative('SELECT id, title FROM articles');
    echo "<p>✅ Articles in DB: " . count($articles) . "</p>";
    
    // 5. Test Repository
    $articleRepo = $container->get('article_repository');
    echo "<p>✅ Repository: " . get_class($articleRepo) . "</p>";
    
    $allArticles = $articleRepo->getAll();
    echo "<p>✅ Repository->getAll(): " . count($allArticles) . " articles</p>";
    
    // 6. Test Use Case
    $useCaseHandler = $container->get('use_case_handler');
    $useCase = $useCaseHandler->get(\Blog\Application\Blog\GetAllArticles::class);
    $result = $useCase->execute([]);
    
    echo "<p>✅ UseCase executed, result type: " . gettype($result) . "</p>";
    
    echo "<h2 style='color: green;'>🎉 APP IS WORKING CORRECTLY!</h2>";
    echo "<p>If API still fails, check:</p>";
    echo "<ul>";
    echo "<li>1. Routes in config/routes.php</li>";
    echo "<li>2. Middleware order</li>";
    echo "<li>3. Controller mapping</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ ERROR</h2>";
    echo "<pre><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<pre><strong>File:</strong> " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "</pre>";
    echo "<pre><strong>Trace:</strong>\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}