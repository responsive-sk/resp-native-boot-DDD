<?php

// boot.php - KOMPLETNÁ VERZIA
declare(strict_types=1);

// Autoloader - POVINNÉ
require_once __DIR__ . '/vendor/autoload.php';

// Environment - ak existuje .env
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
}

// Nastav základné ENV hodnoty (fallback)
$_ENV['APP_ENV'] = $_ENV['APP_ENV'] ?? 'development';
$_ENV['APP_URL'] = $_ENV['APP_URL'] ?? 'http://localhost:8000';
$_ENV['APP_DEBUG'] = $_ENV['APP_DEBUG'] ?? 'true';

// Debug mode (iba development)
if (($_ENV['APP_DEBUG'] ?? 'false') === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
}

// Časová zóna
date_default_timezone_set('Europe/Bratislava');

// UTF-8 všade
mb_internal_encoding('UTF-8');

// === VYTVOR KONTAJNER ===
try {
    // container.php vracia Closure, ktorá vytvára kontajner
    $containerFactory = require __DIR__ . '/config/container.php';
    $container = $containerFactory();

    // Initialize Authorization with Session
    if ($container->has(\ResponsiveSk\Slim4Session\SessionInterface::class)) {
        $session = $container->get(\ResponsiveSk\Slim4Session\SessionInterface::class);
        \Blog\Security\Authorization::setSession($session);
    }

    // Vráť kontajner pre web.php
    return $container;

} catch (Exception $e) {
    if (($_ENV['APP_DEBUG'] ?? 'false') === 'true') {
        throw $e;
    }
    error_log("Boot error: " . $e->getMessage());

    return null;
}
