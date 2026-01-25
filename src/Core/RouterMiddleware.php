<?php

declare(strict_types=1);

namespace Blog\Core;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * RouterMiddleware - middleware ktorý matchuje route a volá handler
 * 
 * Nahradza Chubbyphp\Framework\Middleware\RouteMatcherMiddleware
 */
final readonly class RouterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Router $router
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            // Nájdi matching route
            $routeMatch = $this->router->match($request);

            // Pridaj route parametre do request atribútov
            foreach ($routeMatch->getParams() as $name => $value) {
                $request = $request->withAttribute($name, $value);
            }

            // Pridaj route name do request atribútov (pre templating)
            $request = $request->withAttribute('_route', $routeMatch->getName());

            // Zavolaj route handler
            $routeHandler = $routeMatch->getHandler();
            return $routeHandler($request);

        } catch (RouteNotFoundException $e) {
            // 404 Not Found
            return new Response(
                404,
                ['Content-Type' => 'text/html'],
                $this->render404Page($request, $e)
            );
        }
    }

    /**
     * Vyrenderuje 404 error page
     */
    private function render404Page(ServerRequestInterface $request, RouteNotFoundException $e): string
    {
        $path = htmlspecialchars($request->getUri()->getPath());
        $message = htmlspecialchars($e->getMessage());

        return <<<HTML
        <!DOCTYPE html>
        <html lang="sk">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Stránka nenájdená</title>
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
                    font-size: 3rem;
                }
                p {
                    color: #6b7280;
                    margin: 0 0 1.5rem 0;
                }
                .path {
                    font-family: monospace;
                    background: #f3f4f6;
                    padding: 0.5rem;
                    border-radius: 4px;
                    margin: 1rem 0;
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
                <h1>404</h1>
                <p>Stránka nebola nájdená from RouterMiddleware</p>
                <div class="path">$path</div>
                <p style="font-size: 0.875rem; color: #9ca3af;">$message</p>
                <a href="/">Späť na hlavnú stránku</a>
            </div>
        </body>
        </html>
        HTML;
    }
}

