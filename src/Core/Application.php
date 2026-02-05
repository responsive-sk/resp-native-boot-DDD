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
    
    /**
     * @param array<MiddlewareInterface> $middlewares
     */
    public function __construct(array $middlewares)
    {
        $this->dispatcher = new MiddlewareDispatcher($middlewares);
    }
    
    /**
     * Nastav DebugBar middleware
     */
    public function setDebugBarMiddleware(?\ResponsiveSk\PhpDebugBarMiddleware\DebugBarMiddleware $middleware): void
    {
        $this->debugBarMiddleware = $middleware;
    }
    
    /**
     * Spracuje HTTP request
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Ak máme DebugBar, použime ho ako prvý
        if ($this->debugBarMiddleware !== null) {
            return $this->debugBarMiddleware->process($request, $this->dispatcher);
        }
        
        // Normálne spracovanie
        return $this->dispatcher->dispatch($request);
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