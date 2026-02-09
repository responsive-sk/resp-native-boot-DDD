<?php

declare(strict_types=1);

namespace Blog\Core;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Factory\Psr17Factory;

/**
 * UseCaseHandler - zjednodušuje vykonávanie use-cases v controlleroch
 */
final readonly class UseCaseHandler
{
    public function __construct(
        private ContainerInterface $container,
        private Psr17Factory $responseFactory = new Psr17Factory()
    ) {
    }

    /**
     * Získa use case z kontajnera
     * 
     * @template T of object
     * @param class-string<T> $className
     * @return T
     */
    public function get(string $className): object
    {
        return $this->container->get($className);
    }

    /**
     * Spustí use-case s automatickým mapovaním
     * 
     * @template T of UseCaseInterface
     * @param ServerRequestInterface $request
     * @param T $useCase
     * @param array<string, string> $mappingConfig
     * @param string $responseType 'web', 'api', 'json'
     * @return mixed
     */
    public function execute(
        ServerRequestInterface $request,
        object $useCase,
        array $mappingConfig,
        string $responseType = 'web'
    ): mixed {
        if (!$useCase instanceof UseCaseInterface) {
            throw new \InvalidArgumentException('Use case must implement UseCaseInterface');
        }

        // 1. Mapuj request na use-case vstup
        $input = UseCaseMapper::mapToUseCaseInput($request, $mappingConfig);

        // 2. Spusti use-case
        $result = $useCase->execute($input);

        // 3. Mapuj výstup podľa typu response
        return match ($responseType) {
            'api', 'json' => UseCaseMapper::mapToApiResponse($result),
            'web' => UseCaseMapper::mapToViewData($result),
            default => $result
        };
    }

    /**
     * Vytvorí JSON response pre API
     */
    public function createJsonResponse(mixed $data, int $status = 200): ResponseInterface
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        $response = $this->responseFactory->createResponse($status);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withBody($this->responseFactory->createStream($json));
    }

    /**
     * Vytvorí HTML response pre web
     */
    public function createHtmlResponse(string $html, int $status = 200): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($status);

        return $response
            ->withHeader('Content-Type', 'text/html; charset=utf-8')
            ->withBody($this->responseFactory->createStream($html));
    }

    /**
     * Helper pro vytvoření error response
     */
    public function createErrorResponse(
        string $message,
        int $status = 500,
        ?array $details = null
    ): ResponseInterface {
        $error = [
            'error' => [
                'message' => $message,
                'status' => $status,
                'timestamp' => date('c')
            ]
        ];

        if ($details !== null) {
            $error['error']['details'] = $details;
        }

        return $this->createJsonResponse($error, $status);
    }
}
