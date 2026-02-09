<?php

declare(strict_types=1);

namespace Blog\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class DebugBarWrapper implements MiddlewareInterface
{
    public function __construct(
        private \ResponsiveSk\PhpDebugBarMiddleware\DebugBarMiddleware $debugBarMiddleware
    ) {
    }

    /**
     * @throws \Throwable
     */
    #[\Override]
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        return $this->debugBarMiddleware->process($request, $handler);
    }
}
