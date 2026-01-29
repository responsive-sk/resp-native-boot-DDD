<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Initialize application
$appFactory = require __DIR__ . '/../web.php';

// Get environment
$env = getenv('APP_ENV') ?: 'production';

// Create application
$app = $appFactory($env);

// Create request from globals
$psr17Factory = new Nyholm\Psr7\Factory\Psr17Factory();
$creator = new Nyholm\Psr7Server\ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$request = $creator->fromGlobals();

// Handle request through middleware stack
$response = $app->handle($request);

// Emit response
$app->emit($response);
