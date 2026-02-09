<?php
// config/services/middleware.php
declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    // === ERROR HANDLER ===
    \Blog\Infrastructure\Http\Middleware\ErrorHandlerMiddleware::class => function (ContainerInterface $c) {
        if (class_exists(\Blog\Infrastructure\Http\Middleware\ErrorHandlerMiddleware::class)) {
            if ($c->has(\Blog\Infrastructure\View\ViewRenderer::class)) {
                return new \Blog\Infrastructure\Http\Middleware\ErrorHandlerMiddleware(
                    $c->get(\Blog\Infrastructure\View\ViewRenderer::class)
                );
            }
        }

        // Fallback
        return new class implements \Psr\Http\Server\MiddlewareInterface {
            public function process(
                \Psr\Http\Message\ServerRequestInterface $request,
                \Psr\Http\Server\RequestHandlerInterface $handler
            ): \Psr\Http\Message\ResponseInterface {
                try {
                    return $handler->handle($request);
                } catch (\Throwable $e) {
                    $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
                    return $psr17Factory->createResponse(500)
                        ->withHeader('Content-Type', 'text/plain')
                        ->withBody($psr17Factory->createStream('Internal Error: ' . $e->getMessage()));
                }
            }
        };
    },

    // === SESSION ===
    \Blog\Middleware\SessionMiddleware::class => function (ContainerInterface $c) {
        return new \Blog\Middleware\SessionMiddleware();
    },

    // === CSRF ===
    \Blog\Infrastructure\Http\Middleware\CsrfMiddleware::class => function (ContainerInterface $c) {
        return new \Blog\Infrastructure\Http\Middleware\CsrfMiddleware(
            $c->get(\ResponsiveSk\Slim4Session\Session::class),
            $c->get('config')['session']['csrf'] ?? []
        );
    },

    // === ROUTER ===
    \Blog\Core\RouterMiddleware::class => function (ContainerInterface $c) {
        return new \Blog\Core\RouterMiddleware(
            $c->get(\Blog\Core\Router::class)
        );
    },

    // === CORS ===
    \Blog\Middleware\CorsMiddleware::class => function (ContainerInterface $c) {
        // Correct namespaces
        return new \Blog\Middleware\CorsMiddleware();
    },

    // === HTMX ===
    \Blog\Middleware\HtmxMiddleware::class => function (ContainerInterface $c) {
        return new \Blog\Middleware\HtmxMiddleware();
    },

    // === AUTH ===  
    \Blog\Middleware\AuthMiddleware::class => function (ContainerInterface $c) {
        // AuthMiddleware requires AuthorizationService and AuditLogger
        return new \Blog\Middleware\AuthMiddleware(
            $c->get(\Blog\Security\AuthorizationService::class),
            $c->get(\Blog\Application\Audit\AuditLogger::class)
        );
    },
];
