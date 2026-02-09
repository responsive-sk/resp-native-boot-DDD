<?php

declare(strict_types=1);

namespace Blog\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

/**
 * Middleware Dispatcher - spracováva middleware stack
 *
 * Implementuje PSR-15 RequestHandlerInterface a postupne
 * volá všetky middleware v poradí.
 */
final class MiddlewareDispatcher implements RequestHandlerInterface
{
    private int $index = 0;

    /**
     * @param array<MiddlewareInterface> $middlewares
     */
    public function __construct(
        private array $middlewares
    ) {
    }

    /**
     * Spracuje request cez ďalší middleware v stacku
     */
    #[\Override]
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

    /**
     * Vráti zoznam všetkých middleware
     *
     * @return array<MiddlewareInterface>
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
