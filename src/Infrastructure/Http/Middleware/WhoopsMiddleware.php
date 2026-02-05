<?php
// src/Infrastructure/Http/Middleware/WhoopsMiddleware.php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

/**
 * Middleware pre Whoops error handler (iba development)
 */
final class WhoopsMiddleware implements MiddlewareInterface
{
    private bool $enabled;
    private ?\Whoops\Run $whoops = null;
    
    public function __construct()
    {
        $this->enabled = ($_ENV['APP_ENV'] ?? 'development') === 'development';
        
        if ($this->enabled && class_exists('\Whoops\Run')) {
            $this->initializeWhoops();
        }
    }
    
    private function initializeWhoops(): void
    {
        $this->whoops = new \Whoops\Run();
        
        // HTML handler pre webové požiadavky
        $htmlHandler = new \Whoops\Handler\PrettyPageHandler();
        
        // Pridajme extra informácie pre našu DDD aplikáciu
        $htmlHandler->addDataTable('DDD Application', [
            'Environment' => $_ENV['APP_ENV'] ?? 'unknown',
            'Debug Mode' => ($_ENV['APP_DEBUG'] ?? 'false') === 'true' ? 'ON' : 'OFF',
            'PHP Version' => PHP_VERSION,
            'Project Root' => dirname(__DIR__, 4),
        ]);
        
        // Pridajme custom CSS pre lepší vzhľad
        $htmlHandler->addCustomCss('
            .whoops-container { padding: 20px; }
            .stack-container { background: #f5f5f5; }
            .panel-body { font-family: monospace; }
        ');
        
        $this->whoops->pushHandler($htmlHandler);
        
        // JSON handler pre API požiadavky
        $jsonHandler = new \Whoops\Handler\JsonResponseHandler();
        $jsonHandler->addTraceToOutput(true);
        $this->whoops->pushHandler($jsonHandler);
        
        // Zapni Whoops
        $this->whoops->register();
    }
    
    public function process(
        ServerRequestInterface $request, 
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if (!$this->enabled || $this->whoops === null) {
            return $handler->handle($request);
        }
        
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            // Nech Whoops spracuje výnimku
            $this->whoops->handleException($e);
            exit;
        }
    }
}