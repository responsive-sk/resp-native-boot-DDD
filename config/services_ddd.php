<?php

// config/services_ddd.php - KOMPLETNÁ REFAKTOROVANÁ VERZIA
declare(strict_types=1);

// === IMPORTS (Minimal set for this file) ===
use Psr\Container\ContainerInterface;
use Blog\Core\Application;
use Blog\Core\Router;
use Blog\Core\RouterMiddleware;
use Blog\Core\ExceptionMiddleware;
use Nyholm\Psr7\Factory\Psr17Factory;
use Blog\Infrastructure\View\ViewRenderer;
use Blog\Middleware\CorsMiddleware;
use Blog\Middleware\PjaxMiddleware;
use Blog\Infrastructure\Http\Middleware\RequestContextMiddleware;
use ResponsiveSk\Slim4Session\Middleware\SessionMiddleware as Slim4SessionMiddleware; // Alias to avoid conflict
use Blog\Infrastructure\Http\Middleware\SessionTimeoutMiddleware;
use Blog\Infrastructure\Http\Middleware\RateLimitMiddleware;
use Blog\Infrastructure\Http\Middleware\CsrfMiddleware;
use Blog\Middleware\AuthMiddleware;
use Blog\Middleware\ApiAuthMiddleware;
use Blog\Infrastructure\Http\Middleware\ErrorHandlerMiddleware;
use ResponsiveSk\Slim4Paths\Paths; // Used in SessionTimeoutMiddleware
use Blog\Security\SecurityLogger; // Used in SessionTimeoutMiddleware and CsrfMiddleware
use Blog\Security\CsrfProtection; // Used in CsrfMiddleware
use Blog\Application\Audit\AuditLogger; // Used in AuthMiddleware and ApiAuthMiddleware


$services = array_merge(
    require __DIR__ . '/services/core.php',
    require __DIR__ . '/services/view.php',
    require __DIR__ . '/services/security.php',
    require __DIR__ . '/services/repositories.php',
    require __DIR__ . '/services/session.php',
    require __DIR__ . '/services/cloudinary.php',
    require __DIR__ . '/services/use_cases.php',
    require __DIR__ . '/services/controllers.php'
);

// === FÁZA 8: ROUTER ===
$services += [
    Router::class => fn (ContainerInterface $c) => (require __DIR__ . '/routes.php')($c),
    'router' => fn ($c) => $c->get(Router::class), // Alias
];

// === FÁZA 8: MIDDLEWARE ===
$services += [
    RouterMiddleware::class => fn ($c) => new RouterMiddleware($c->get('router')),

    ExceptionMiddleware::class => fn (ContainerInterface $c) => new ExceptionMiddleware(
        $c->get(Psr17Factory::class),
        $c->get(ViewRenderer::class)
    ),

    AuthMiddleware::class => fn (ContainerInterface $c) => new AuthMiddleware(
        $c->get(\Blog\Security\AuthorizationService::class),
        $c->get(AuditLogger::class)
    ),
    ApiAuthMiddleware::class => fn (ContainerInterface $c) => new ApiAuthMiddleware(
        $c->get(\Blog\Security\AuthorizationService::class),
        $c->get(AuditLogger::class)
    ),
    ErrorHandlerMiddleware::class => fn (ContainerInterface $c) => new ErrorHandlerMiddleware(
        $c->get(ViewRenderer::class)
    ),
    RequestContextMiddleware::class => fn () => new RequestContextMiddleware(),
    SessionTimeoutMiddleware::class => fn (ContainerInterface $c) => new SessionTimeoutMiddleware(
        null,
        $c->get(Paths::class),
        $c->get(SecurityLogger::class)
    ),
    CsrfMiddleware::class => fn (ContainerInterface $c) => new CsrfMiddleware(
        $c->get(CsrfProtection::class),
        $c->get(SecurityLogger::class)
    ),
    RateLimitMiddleware::class => fn (ContainerInterface $c) => new RateLimitMiddleware(
        require __DIR__ . '/ratelimit.php'
    ),
    CorsMiddleware::class => fn () => new CorsMiddleware(),
    PjaxMiddleware::class => fn () => new \Blog\Middleware\PjaxMiddleware(),
];

// === FÁZA 9: MIDDLEWARE STACK ===
$services['middlewares'] = function (ContainerInterface $c) {
    $middlewares = [];

    // Základný middleware stack
    $middlewares = array_merge($middlewares, [
        $c->get(ErrorHandlerMiddleware::class),
        $c->get(ExceptionMiddleware::class),
        $c->get(CorsMiddleware::class),
        $c->get(PjaxMiddleware::class),
        $c->get(RequestContextMiddleware::class),
        $c->get(Slim4SessionMiddleware::class), // slim4-session
        $c->get(SessionTimeoutMiddleware::class),
        $c->get(RateLimitMiddleware::class),
        $c->get(CsrfMiddleware::class),
        $c->get(AuthMiddleware::class),
        $c->get(ApiAuthMiddleware::class), // API authentication
        $c->get(RouterMiddleware::class),
    ]);

    return $middlewares;
};

// === FÁZA 10: APPLICATION ===
$services[Application::class] = function (ContainerInterface $c) {
    $app = new Application($c->get('middlewares'));

    return $app;
};

// === Vráť finálny array služieb ===
return $services;