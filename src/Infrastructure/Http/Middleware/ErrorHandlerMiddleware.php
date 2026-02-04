<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Middleware;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

final class ErrorHandlerMiddleware implements MiddlewareInterface
{
    private string $env;
    
    public function __construct(
        private \Blog\Infrastructure\View\ViewRenderer $viewRenderer
    ) {
        // ✅ Načíta APP_ENV z $_ENV (ktorý je nastavený v boot.php)
        $this->env = $_ENV['APP_ENV'] ?? 'development';
        
        // ✅ Log init
        if ($this->env === 'development') {
            error_log("[ErrorHandler] Initialized for environment: {$this->env}");
        }
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        try {
            $response = $handler->handle($request);

            // Production: Remove any error output from response body
            if ($this->env === 'production') {
                $body = (string) $response->getBody();

                // Remove PHP warnings/notices/errors from output
                $body = preg_replace(
                    '/<b>(Warning|Notice|Error|Fatal error|Parse error):<\/b>.*?<br \/>/is',
                    '',
                    $body
                );

                // Remove stack traces
                $body = preg_replace(
                    '/Stack trace:.*?#\d+.*?$/ms',
                    '',
                    $body
                );

                // Create new response with cleaned body
                $newResponse = new Response(
                    $response->getStatusCode(),
                    $response->getHeaders(),
                    $body
                );

                return $newResponse;
            }

            return $response;

        } catch (Throwable $e) {
            // Log error
            error_log(sprintf(
                '[%s] %s in %s:%d',
                get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));

            // Determine status code
            $statusCode = 500;

            if ($e instanceof \Blog\Domain\Common\Exception\ResourceNotFoundException) {
                $statusCode = 404;
            }
            // Add other exception mapping as needed

            // Render error page via ViewRenderer
            // In DEV, pass the exception object so ViewRenderer can extract trace info
            $data = [];

            if ($this->env !== 'production') {
                $data['exception'] = $e;
            }

            return $this->viewRenderer->renderErrorResponse(
                $statusCode,
                $this->env === 'production' ? 'An unexpected error occurred.' : $e->getMessage(),
                $data
            );
        }
    }
}
