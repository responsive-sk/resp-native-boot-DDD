<?php
// src/Core/UseCaseHandler.php

declare(strict_types=1);

namespace Blog\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * UseCaseHandler - zjednodušuje vykonávanie use-cases v controlleroch
 */
final class UseCaseHandler
{
    public function __construct(
        private UseCaseMapper $mapper
    ) {}
    
    /**
     * Spustí use-case s automatickým mapovaním
     */
    public function execute(
        ServerRequestInterface $request,
        callable $useCase,
        array $mappingConfig,
        string $responseType = 'web' // 'web', 'api', 'json'
    ) {
        // 1. Mapuj request na use-case vstup
        $input = $this->mapper->mapToUseCaseInput($request, $mappingConfig);
        
        // 2. Spusti use-case
        $result = $useCase($input);
        
        // 3. Mapuj výstup podľa typu response
        return match($responseType) {
            'api', 'json' => $this->mapper->mapToApiResponse($result),
            'web' => $this->mapper->mapToViewData($result),
            default => $result
        };
    }
    
    /**
     * Vytvorí JSON response pre API
     */
    public function createJsonResponse($data, int $status = 200): ResponseInterface
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        // Použijeme Nyholm factory
        $factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $response = $factory->createResponse($status);
        
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withBody($factory->createStream($json));
    }
    
    /**
     * Vytvorí HTML response pre web
     */
    public function createHtmlResponse(string $html, int $status = 200): ResponseInterface
    {
        $factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $response = $factory->createResponse($status);
        
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withBody($factory->createStream($html));
    }
}