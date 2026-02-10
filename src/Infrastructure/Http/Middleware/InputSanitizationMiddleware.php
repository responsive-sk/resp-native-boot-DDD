<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class InputSanitizationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Sanitize query parameters
        $queryParams = $this->sanitizeArray($request->getQueryParams());
        
        // Sanitize parsed body
        $parsedBody = $request->getParsedBody();
        if (is_array($parsedBody)) {
            $parsedBody = $this->sanitizeArray($parsedBody);
        }

        // Create new request with sanitized data
        $request = $request
            ->withQueryParams($queryParams)
            ->withParsedBody($parsedBody);

        return $handler->handle($request);
    }

    private function sanitizeArray(array $data): array
    {
        return array_map([$this, 'sanitizeValue'], $data);
    }

    private function sanitizeValue(mixed $value): mixed
    {
        if (is_string($value)) {
            // Remove potential HTML tags and normalize whitespace
            $value = trim($value);
            
            // Remove null bytes and control characters except newlines and tabs
            $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
            
            // Limit string length to prevent DoS
            if (strlen($value) > 10000) {
                throw new \InvalidArgumentException('Input too long');
            }
            
            return $value;
        }

        if (is_array($value)) {
            return $this->sanitizeArray($value);
        }

        return $value;
    }
}