<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;

// 1. Načítaj boot.php - získaj kontajner
$container = require __DIR__ . '/boot.php';

// 2. Vytvor app z kontajnera
try {
    $app = $container->get(\Blog\Core\Application::class);
} catch (Throwable $e) {
    handleFatalError($e);
    exit(1);
}

// 3. Spracuj request
try {
    $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
    $creator = new \Nyholm\Psr7Server\ServerRequestCreator(
        $psr17Factory, // ServerRequestFactory
        $psr17Factory, // UriFactory
        $psr17Factory, // UploadedFileFactory
        $psr17Factory  // StreamFactory
    );

    $request = $creator->fromGlobals();

    $response = $app->handle($request);
    $app->emit($response);

} catch (Throwable $e) {
    handleRuntimeError($e, $container);
}

/**
 * Handle fatal application errors
 */
function handleFatalError(Throwable $e): never
{
    if (($_ENV['APP_DEBUG'] ?? 'false') === 'true') {
        // Debug mode - show details
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');

        echo '<h1>Application Error</h1>';
        echo '<pre>';
        echo htmlspecialchars((string) $e, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5);
        echo '</pre>';
        exit(1);
    }

    // Production mode - minimal error
    error_log(sprintf(
        'Fatal error: %s in %s:%d',
        $e->getMessage(),
        $e->getFile(),
        $e->getLine()
    ));

    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Internal Server Error';
    exit(1);
}

/**
 * Handle runtime errors
 */
function handleRuntimeError(Throwable $e, ?ContainerInterface $container = null): never
{
    // Try to use error handler from container if available
    if ($container && $container->has(\Blog\Core\ErrorHandlerInterface::class)) {
        try {
            $errorHandler = $container->get(\Blog\Core\ErrorHandlerInterface::class);
            $response = $errorHandler->handle($e);

            if (!headers_sent()) {
                http_response_code($response->getStatusCode());
                foreach ($response->getHeaders() as $name => $values) {
                    foreach ($values as $value) {
                        header(sprintf('%s: %s', $name, $value), false);
                    }
                }
                echo (string) $response->getBody();
            }
            exit(1);
        } catch (Throwable $handlerError) {
            // Fallback to basic error handling
        }
    }

    // Basic error handling
    handleFatalError($e);
}
