<?php
// src/Infrastructure/Http/Controller/BaseController.php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller;

use Blog\Core\UseCaseHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * BaseController so základnou funkcionalitou pre všetky controllery
 */
abstract class BaseController
{
    protected UseCaseHandler $useCaseHandler;
    protected ContainerInterface $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->useCaseHandler = $container->get(UseCaseHandler::class);
    }
    
    /**
     * Rýchle vykonanie use-case s mapovaním
     */
    protected function executeUseCase(
        ServerRequestInterface $request,
        object $useCase,
        array $mapping,
        string $responseType = 'web'
    ) {
        return $this->useCaseHandler->execute($request, $useCase, $mapping, $responseType);
    }
    
    /**
     * Vytvorí JSON response
     */
    protected function jsonResponse($data, int $status = 200): ResponseInterface
    {
        return $this->useCaseHandler->createJsonResponse($data, $status);
    }
    
    /**
     * Vytvorí HTML response
     */
    protected function htmlResponse(string $html, int $status = 200): ResponseInterface
    {
        return $this->useCaseHandler->createHtmlResponse($html, $status);
    }
    
    /**
     * Presmerovanie
     */
    protected function redirect(string $url, int $status = 302): ResponseInterface
    {
        $factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $response = $factory->createResponse($status);
        
        return $response->withHeader('Location', $url);
    }
}