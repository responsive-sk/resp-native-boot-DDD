<?php
declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller;

use Blog\Core\UseCaseHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class BaseController
{
    public function __construct(
        protected ContainerInterface $container,
        protected UseCaseHandler $useCaseHandler
    ) {}
    
    /**
     * Zjednodušené vykonanie use-case
     */
    protected function executeUseCase(
        ServerRequestInterface $request,
        callable $useCase,
        array $mappingConfig,
        string $responseType = 'web'
    ) {
        return $this->useCaseHandler->execute(
            $request,
            $useCase,
            $mappingConfig,
            $responseType
        );
    }
    
    /**
     * JSON response helper
     */
    protected function jsonResponse($data, int $status = 200): ResponseInterface
    {
        return $this->useCaseHandler->createJsonResponse($data, $status);
    }
    
    /**
     * HTML response helper
     */
    protected function htmlResponse(string $html, int $status = 200): ResponseInterface
    {
        return $this->useCaseHandler->createHtmlResponse($html, $status);
    }
    
    /**
     * Get service from container
     */
    protected function get(string $id): mixed
    {
        return $this->container->get($id);
    }
    
    /**
     * Redirect helper
     */
    protected function redirect(string $url, int $status = 302): ResponseInterface
    {
        $factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $response = $factory->createResponse($status);
        
        return $response->withHeader('Location', $url);
    }
}