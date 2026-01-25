<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Nyholm\Psr7\Response;

final class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private string $env
    ) {}

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

        } catch (\Throwable $e) {
            // Production: Generic error page
            if ($this->env === 'production') {
                $html = <<<HTML
                    <!DOCTYPE html>
                    <html lang="sk">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Chyba - ChubbyBlog</title>
                        <style>
                            body {
                                font-family: system-ui, -apple-system, sans-serif;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                min-height: 100vh;
                                margin: 0;
                                background: #f3f4f6;
                            }
                            .error-container {
                                text-align: center;
                                padding: 2rem;
                                background: white;
                                border-radius: 8px;
                                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                                max-width: 500px;
                            }
                            h1 {
                                color: #dc2626;
                                margin: 0 0 1rem 0;
                            }
                            p {
                                color: #6b7280;
                                margin: 0 0 1.5rem 0;
                            }
                            a {
                                display: inline-block;
                                padding: 0.75rem 1.5rem;
                                background: #3b82f6;
                                color: white;
                                text-decoration: none;
                                border-radius: 6px;
                            }
                            a:hover {
                                background: #2563eb;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="error-container">
                            <h1>Niečo sa pokazilo</h1>
                            <p>Ospravedlňujeme sa, ale nastala neočakávaná chyba. Skúste to prosím neskôr.</p>
                            <a href="<?= path('/') ?>">Späť na hlavnú stránku</a>
                        </div>
                    </body>
                    </html>
                    HTML;

                // Log error (without exposing to user)
                error_log(sprintf(
                    '[%s] %s in %s:%d',
                    get_class($e),
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine()
                ));

                return new Response(500, ['Content-Type' => 'text/html'], $html);
            }

            // Development: Show full error
            throw $e;
        }
    }
}
