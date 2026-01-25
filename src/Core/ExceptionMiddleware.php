<?php

declare(strict_types=1);

namespace Blog\Core;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

/**
 * ExceptionMiddleware - zachytáva exceptions a konvertuje ich na HTTP response
 * 
 * Nahradza Chubbyphp\Framework\Middleware\ExceptionMiddleware
 */
final readonly class ExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private bool $debug = false
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    private function handleException(Throwable $e): ResponseInterface
    {
        $statusCode = $this->getStatusCode($e);

        if ($this->debug) {
            // Debug mode: zobraz detailné info o chybe
            $body = $this->renderDebugPage($e);
        } else {
            // Production mode: zobraz generickú error page
            $body = $this->renderProductionPage($statusCode);
        }

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($body);

        return $response->withHeader('Content-Type', 'text/html');
    }

    private function getStatusCode(Throwable $e): int
    {
        // Ak exception má getStatusCode() metódu, použi ju
        if (method_exists($e, 'getStatusCode')) {
            return $e->getStatusCode();
        }

        // Inak použij 500 Internal Server Error
        return 500;
    }

    private function renderDebugPage(Throwable $e): string
    {
        $class = htmlspecialchars(get_class($e));
        $message = htmlspecialchars($e->getMessage());
        $file = htmlspecialchars($e->getFile());
        $line = $e->getLine();
        $trace = htmlspecialchars($e->getTraceAsString());

        return <<<HTML
        <!DOCTYPE html>
        <html lang="sk">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Exception: $class</title>
            <style>
                body {
                    font-family: system-ui, -apple-system, sans-serif;
                    margin: 0;
                    padding: 2rem;
                    background: #1e1e1e;
                    color: #d4d4d4;
                }
                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                }
                h1 {
                    color: #f87171;
                    margin: 0 0 1rem 0;
                }
                .exception-class {
                    color: #fbbf24;
                    font-size: 0.875rem;
                    margin-bottom: 1rem;
                }
                .message {
                    background: #2d2d2d;
                    padding: 1rem;
                    border-radius: 4px;
                    margin-bottom: 1rem;
                    border-left: 4px solid #f87171;
                }
                .location {
                    color: #9ca3af;
                    font-size: 0.875rem;
                    margin-bottom: 2rem;
                }
                .trace {
                    background: #2d2d2d;
                    padding: 1rem;
                    border-radius: 4px;
                    overflow-x: auto;
                    white-space: pre-wrap;
                    font-family: 'Courier New', monospace;
                    font-size: 0.875rem;
                    line-height: 1.5;
                }
                h2 {
                    color: #60a5fa;
                    margin: 2rem 0 1rem 0;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Exception Occurred</h1>
                <div class="exception-class">$class</div>
                <div class="message">$message</div>
                <div class="location">in $file on line $line</div>
                <h2>Stack Trace</h2>
                <div class="trace">$trace</div>
            </div>
        </body>
        </html>
        HTML;
    }

    private function renderProductionPage(int $statusCode): string
    {
        $title = $statusCode === 404 ? 'Stránka nenájdená' : 'Chyba servera';
        $message = $statusCode === 404 
            ? 'Požadovaná stránka nebola nájdená.' 
            : 'Ospravedlňujeme sa, ale nastala neočakávaná chyba.';

        return <<<HTML
        <!DOCTYPE html>
        <html lang="sk">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>$title</title>
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
                <h1>$title</h1>
                <p>$message</p>
                <a href="/">Späť na hlavnú stránku</a>
            </div>
        </body>
        </html>
        HTML;
    }
}

