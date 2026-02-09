<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Middleware;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RateLimitMiddleware implements MiddlewareInterface
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Check if rate limiting is globally enabled
        if (!($this->config['enabled'] ?? true)) {
            return $handler->handle($request);
        }

        $path = $request->getUri()->getPath();
        $method = $request->getMethod();
        $ip = $this->getClientIp($request);

        // Determine rate limit type
        $limitType = $this->getLimitType($path, $method);

        // Check if rate limiting is enabled for this specific type
        if (!($this->config[$limitType]['enabled'] ?? true)) {
            return $handler->handle($request);
        }

        // Skip rate limiting for safe methods and API endpoints
        if (!in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']) || str_starts_with($path, '/api/')) {
            return $handler->handle($request);
        }

        // Check rate limit
        if ($this->isRateLimited($ip, $limitType)) {
            return $this->createRateLimitResponse($limitType);
        }

        return $handler->handle($request);
    }

    private function getLimitType(string $path, string $method): string
    {
        if ($method === 'POST' && str_ends_with($path, '/login')) {
            return 'login';
        }

        if ($method === 'POST' && str_ends_with($path, '/register')) {
            return 'register';
        }

        return 'default';
    }

    private function isRateLimited(string $ip, string $limitType): bool
    {
        $limit = $this->config[$limitType]; // Use injected config
        $key = "rate_limit_{$limitType}_{$ip}";
        $lockoutKey = "rate_limit_lockout_{$limitType}_{$ip}";

        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $now = time();

        // Check if currently locked out
        if (isset($_SESSION[$lockoutKey]) && $_SESSION[$lockoutKey] > $now) {
            return true;
        }

        // Clear expired lockout
        if (isset($_SESSION[$lockoutKey]) && $_SESSION[$lockoutKey] <= $now) {
            unset($_SESSION[$lockoutKey]);
            unset($_SESSION[$key]); // Reset attempts after lockout
        }

        // Initialize attempts tracking
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['attempts' => 0, 'window_start' => $now];
        }

        $attempts = &$_SESSION[$key];

        // Reset window if expired
        if ($now - $attempts['window_start'] > $limit['window']) {
            $attempts = ['attempts' => 0, 'window_start' => $now];
        }

        // Increment attempts
        $attempts['attempts']++;

        // Check if limit exceeded
        if ($attempts['attempts'] > $limit['attempts']) {
            // Apply lockout if configured
            if (isset($limit['lockout'])) {
                $_SESSION[$lockoutKey] = $now + $limit['lockout'];
            }

            return true;
        }

        return false;
    }

    private function getClientIp(ServerRequestInterface $request): string
    {
        $serverParams = $request->getServerParams();

        // Check for forwarded headers
        $headers = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR',
        ];

        foreach ($headers as $header) {
            $ip = $serverParams[$header] ?? null;
            if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }

        return $serverParams['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    private function createRateLimitResponse(string $limitType): ResponseInterface
    {
        $limit = $this->config[$limitType]; // Use injected config
        $response = new Response();

        // Check if AJAX request
        if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
            $response->getBody()->write(json_encode([
                'error' => 'Too many requests',
                'code' => 'RATE_LIMIT_EXCEEDED',
                'limit' => $limit['attempts'],
                'window' => $limit['window'],
                'lockout' => $limit['lockout'] ?? null,
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Retry-After', (string) ($limit['lockout'] ?? $limit['window']))
                ->withStatus(429);
        }

        // For regular requests, show error page
        $lockoutMinutes = ($limit['lockout'] ?? $limit['window']) / 60;

        $response->getBody()->write("
            <!DOCTYPE html>
            <html>
            <head>
                <title>Rate Limit Exceeded</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
                    .error { color: #e74c3c; }
                    .info { color: #3498db; }
                </style>
            </head>
            <body>
                <h1 class='error'>Rate Limit Exceeded</h1>
                <p class='info'>Too many attempts. Please try again in {$lockoutMinutes} minutes.</p>
                <button onclick='history.back()'>Go Back</button>
            </body>
            </html>
        ");

        return $response
            ->withHeader('Retry-After', (string) ($limit['lockout'] ?? $limit['window']))
            ->withStatus(429);
    }

    public function getRemainingAttempts(string $ip, string $limitType): array
    {
        $limit = $this->config[$limitType]; // Use injected config
        $key = "rate_limit_{$limitType}_{$ip}";

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION[$key])) {
            return ['attempts' => 0, 'remaining' => $limit['attempts']];
        }

        $attempts = $_SESSION[$key]['attempts'];
        $remaining = max(0, $limit['attempts'] - $attempts);

        return ['attempts' => $attempts, 'remaining' => $remaining];
    }
}
