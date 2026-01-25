<?php

declare(strict_types=1);

namespace Blog\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

/**
 * Middleware Dispatcher - processes middleware stack
 * 
 * Implements PSR-15 RequestHandlerInterface and sequentially
 * calls all middleware in order.
 */
final class MiddlewareDispatcher implements RequestHandlerInterface
{
    /**
     * @var array<MiddlewareInterface>
     */
    private array $middlewares;

    private int $index = 0;

    /**
     * @param array<MiddlewareInterface> $middlewares
     */
    public function __construct(array $middlewares)
    {
        $this->middlewares = $middlewares;
    }

    /**
     * Process request through the next middleware in stack
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!isset($this->middlewares[$this->index])) {
            throw new RuntimeException('No middleware left to execute. Make sure RouterMiddleware is in the stack.');
        }

        $middleware = $this->middlewares[$this->index];
        $this->index++;

        return $middleware->process($request, $this);
    }

    /**
     * Spustí celý middleware stack od začiatku
     */
    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        $this->index = 0;
        return $this->handle($request);
    }
}

