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
$request = \Nyholm\Psr7\ServerRequest::fromGlobals();
$response = $app->handle($request);

// 5. Pošli response
(new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);
