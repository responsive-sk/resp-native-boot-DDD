<?php

// web.php - OPRAVENÁ VERZIA
declare(strict_types=1);

// 1. Načítaj boot.php - získaj kontajner
$container = require __DIR__ . '/boot.php';

if ($container === null) {
    // boot.php zlyhal
    http_response_code(500);
    echo "Application boot failed";
    exit;
}

// 2. Vytvor app z kontajnera
$app = $container->get(\Blog\Core\Application::class);

// 3. Spracuj request
$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$creator = new \Nyholm\Psr7Server\ServerRequestCreator(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);
$request = $creator->fromGlobals();

try {
    $response = $app->handle($request);
    $app->emit($response);
} catch (Throwable $e) {
    // Emergency error handling
    http_response_code(500);
    header('Content-Type: text/plain');
    echo "Application error: " . $e->getMessage();

    // Log
    error_log("Application error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
}
