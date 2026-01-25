<?php

declare(strict_types=1);

namespace Blog\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Native PHP Application - replaces Chubbyphp\Framework\Application
 * 
 * Processes HTTP request through middleware stack and emits response.
 */
final class Application
{
    private MiddlewareDispatcher $dispatcher;

    /**
     * @param array<MiddlewareInterface> $middlewares
     */
    public function __construct(array $middlewares)
    {
        $this->dispatcher = new MiddlewareDispatcher($middlewares);
    }

    /**
     * Process HTTP request through middleware stack
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->dispatcher->dispatch($request);
    }

    /**
     * Emituje HTTP response (headers + body) v index.php a web.php
     */
    public function emit(ResponseInterface $response): void
    {
        // Nastaviť HTTP status code
        if (!headers_sent()) {
            http_response_code($response->getStatusCode());

            // Emitovať všetky headers
            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header(sprintf('%s: %s', $name, $value), false);
                }
            }
        }

        // Emitovať response body
        echo (string) $response->getBody();
    }
}

