<?php
// config/services_ddd.php
declare(strict_types=1);

// Načti všechny service konfigurace
$cloudinaryServices = require __DIR__ . '/services/cloudinary.php';
$controllerServices = require __DIR__ . '/services/controllers.php';
$coreServices = require __DIR__ . '/services/core.php';
$imageServices = require __DIR__ . '/services/image.php';
$repositoryServices = require __DIR__ . '/services/repositories.php';
$securityServices = require __DIR__ . '/services/security.php';
$sessionServices = require __DIR__ . '/services/session.php';
$useCaseServices = require __DIR__ . '/services/use_cases.php';
$viewServices = require __DIR__ . '/services/view.php';

// Načti middleware services
$middlewareServices = require __DIR__ . '/services/middleware.php';

// Slouč všechny services do jednoho pole
$allServices = array_merge(
    $cloudinaryServices,
    $controllerServices,
    $coreServices,
    $imageServices,
    $repositoryServices,
    $securityServices,
    $sessionServices,
    $useCaseServices,
    $viewServices,
    $middlewareServices
);

// Zkontroluj že Application je definována
if (!isset($allServices['Blog\Core\Application'])) {
    throw new RuntimeException('Blog\Core\Application is not defined in services!');
}

// Zkontroluj že Router je definován (přidáme pokud chybí)
if (!isset($allServices['Blog\Core\Router'])) {
    $allServices['Blog\Core\Router'] = require __DIR__ . '/routes.php';
}

return $allServices;
