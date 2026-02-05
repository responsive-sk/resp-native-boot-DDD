<?php
declare(strict_types=1);

namespace Blog\Core;

use Psr\Http\Message\ServerRequestInterface;

abstract class BaseUseCase implements UseCaseInterface
{
    /**
     * Validate input data
     *
     * @param array<string, mixed> $input
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function validate(array $input): void
    {
        // Override in child classes for specific validation
    }
    
    /**
     * Extract data from PSR-7 request
     *
     * @param ServerRequestInterface $request
     * @param array<string, string> $mapping
     * @return array<string, mixed>
     */
    protected function extractFromRequest(ServerRequestInterface $request, array $mapping): array
    {
        $input = [];
        
        foreach ($mapping as $targetKey => $source) {
            $input[$targetKey] = $this->getValueFromRequest($request, $source);
        }
        
        return $input;
    }
    
    /**
     * Get value from request based on mapping syntax
     *
     * @param ServerRequestInterface $request
     * @param string $source  Format: "type:key" or "type:key:subkey"
     * @return mixed
     */
    private function getValueFromRequest(ServerRequestInterface $request, string $source): mixed
    {
        $parts = explode(':', $source);
        $type = $parts[0] ?? '';
        $key = $parts[1] ?? '';
        $subKey = $parts[2] ?? null;
        
        return match ($type) {
            'body' => $this->getBodyValue($request, $key, $subKey),
            'query' => $this->getQueryValue($request, $key, $subKey),
            'route' => $request->getAttribute($key),
            'session' => $this->getSessionValue($key, $subKey),
            'file' => $this->getFileValue($request, $key),
            'header' => $request->getHeaderLine($key),
            default => null
        };
    }
    
    /**
     * Get value from request body
     */
    private function getBodyValue(ServerRequestInterface $request, string $key, ?string $subKey = null): mixed
    {
        $body = (string) $request->getBody();
        $data = json_decode($body, true) ?? [];
        
        if ($subKey) {
            return $data[$key][$subKey] ?? null;
        }
        
        return $data[$key] ?? null;
    }
    
    /**
     * Get value from query parameters
     */
    private function getQueryValue(ServerRequestInterface $request, string $key, ?string $subKey = null): mixed
    {
        $query = $request->getQueryParams();
        
        if ($subKey) {
            return $query[$key][$subKey] ?? null;
        }
        
        return $query[$key] ?? null;
    }
    
    /**
     * Get value from session
     */
    private function getSessionValue(string $key, ?string $subKey = null): mixed
    {
        if (!isset($_SESSION)) {
            return null;
        }
        
        if ($subKey) {
            return $_SESSION[$key][$subKey] ?? null;
        }
        
        return $_SESSION[$key] ?? null;
    }
    
    /**
     * Get uploaded file from request
     */
    private function getFileValue(ServerRequestInterface $request, string $key): ?\Psr\Http\Message\UploadedFileInterface
    {
        $uploadedFiles = $request->getUploadedFiles();
        return $uploadedFiles[$key] ?? null;
    }
    
    /**
     * Create success response
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function success(array $data = []): array
    {
        return array_merge(['success' => true], $data);
    }
    
    /**
     * Create error response
     *
     * @param string $message
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function error(string $message, array $data = []): array
    {
        return array_merge(['success' => false, 'error' => $message], $data);
    }
}
