<?php
declare(strict_types=1);

// 1. Načítaj boot
require_once __DIR__ . '/boot.php';

// 2. Session setup (web-specific)
$sessionName = $_ENV['SESSION_NAME'] ?? 'resp_session';
session_name($sessionName);

// 3. Vytvor app
$containerFactory = require __DIR__ . '/config/container.php';
$container = $containerFactory();
$app = $container->get(Blog\Core\Application::class);

// 4. Spracuj request
$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$creator = new \Nyholm\Psr7Server\ServerRequestCreator(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);
$request = $creator->fromGlobals();
$response = $app->handle($request);

// 5. Pošli response - ZJEDNODUŠENÁ VERZIA bez laminas
$statusCode = $response->getStatusCode();
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
http_response_code($statusCode);
echo $response->getBody();