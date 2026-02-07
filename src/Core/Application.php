<?php

// src/Core/Application.php - UPRAVENÁ PRE DEBUGBAR

declare(strict_types=1);

namespace Blog\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;



final class Application implements RequestHandlerInterface
{
    private MiddlewareDispatcher $dispatcher;
    private ?\ResponsiveSk\PhpDebugBarMiddleware\DebugBarMiddleware $debugBarMiddleware = null;
    private array $additionalMiddlewares = [];

    /**
     * @param array<MiddlewareInterface> $middlewares
     */
    public function __construct(array $middlewares)
    {
        $this->dispatcher = new MiddlewareDispatcher($middlewares);
    }

    /**
     * Pridá middleware do aplikácie
     */
    public function add(MiddlewareInterface $middleware): void
    {
        $this->additionalMiddlewares[] = $middleware;
    }

    /**
     * Spracuje HTTP request
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Vytvor nový dispatcher s pridanými middlewares
        $allMiddlewares = array_merge($this->additionalMiddlewares, $this->dispatcher->getMiddlewares());
        $newDispatcher = new MiddlewareDispatcher($allMiddlewares);

        // Ak máme DebugBar, použime ho ako prvý
        if ($this->debugBarMiddleware !== null) {
            return $this->debugBarMiddleware->process($request, $newDispatcher);
        }

        // Normálne spracovanie
        return $newDispatcher->dispatch($request);
    }

    /**
     * Emituje HTTP response
     */
    public function emit(ResponseInterface $response): void
    {
        if (!headers_sent()) {
            http_response_code($response->getStatusCode());

            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header(sprintf('%s: %s', $name, $value), false);
                }
            }
        }

        echo (string) $response->getBody();
    }
}
