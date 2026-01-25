<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware that stores PSR-7 Request in global state for MoonShine RequestContract
 */
final class RequestContextMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Store request in global state so MoonShine RequestContract can access it
        $_SERVER['__PSR7_REQUEST'] = $request;
        
        try {
            return $handler->handle($request);
        } finally {
            // Clean up
            unset($_SERVER['__PSR7_REQUEST']);
        }
    }
}

