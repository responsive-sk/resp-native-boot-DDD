<?php

declare(strict_types=1);

namespace Blog\Core;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Http\Message\ServerRequestInterface;

use function FastRoute\simpleDispatcher;

/**
 * Native PHP Router - wrapper pre FastRoute
 *
 * Nahradza Chubbyphp\Framework\Router
 */
final class Router
{
    /**
     * @var array<array{method: string, path: string, name: string, handler: callable}>
     */
    private array $routes = [];

    private ?Dispatcher $dispatcher = null;

    /**
     * Pridá route do routera
     */
    public function addRoute(string $method, string $path, string $name, callable $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'name' => $name,
            'handler' => $handler,
        ];
    }

    /**
     * Pridá GET route
     */
    public function get(string $path, string $name, callable $handler): void
    {
        $this->addRoute('GET', $path, $name, $handler);
    }

    /**
     * Pridá POST route
     */
    public function post(string $path, string $name, callable $handler): void
    {
        $this->addRoute('POST', $path, $name, $handler);
    }

    /**
     * Pridá PUT route
     */
    public function put(string $path, string $name, callable $handler): void
    {
        $this->addRoute('PUT', $path, $name, $handler);
    }

    /**
     * Pridá DELETE route
     */
    public function delete(string $path, string $name, callable $handler): void
    {
        $this->addRoute('DELETE', $path, $name, $handler);
    }

    /**
     * Pridá PATCH route
     */
    public function patch(string $path, string $name, callable $handler): void
    {
        $this->addRoute('PATCH', $path, $name, $handler);
    }

    /**
     * Nájde matching route pre request
     */
    public function match(ServerRequestInterface $request): RouteMatch
    {
        if ($this->dispatcher === null) {
            $this->buildDispatcher();
        }

        if ($this->dispatcher === null) {
            throw new \RuntimeException('Router dispatcher failed to initialize');
        }

        // Normalizuj cestu
        $normalizedPath = $this->normalizePath($request->getUri()->getPath());

        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(),
            $normalizedPath
        );

        if ($routeInfo[0] === Dispatcher::FOUND) {
            $route = $routeInfo[1];
            $params = $routeInfo[2];

            return new RouteMatch(
                $route['name'],
                $route['handler'],
                $params
            );
        }

        if ($routeInfo[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            throw new RouteNotFoundException(
                sprintf('Method not allowed for path: %s', $request->getUri()->getPath())
            );
        }

        throw new RouteNotFoundException(
            sprintf('Route not found: %s %s', $request->getMethod(), $request->getUri()->getPath())
        );
    }

    /**
     * Normalizuje URL cestu
     * - Odstráni koncové lomítko (okrem root "/")
     * - Zabezpečí konzistentné porovnávanie
     */
    private function normalizePath(string $path): string
    {
        // Odstráň koncové lomítko, okrem root
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        // Zabezpeč, že prázdna cesta je root
        if ($path === '') {
            $path = '/';
        }

        return $path;
    }

    /**
     * Vytvorí FastRoute dispatcher
     */
    private function buildDispatcher(): void
    {
        $this->dispatcher = simpleDispatcher(function (RouteCollector $r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route['method'], $route['path'], $route);
            }
        });
    }

    /**
     * Vráti všetky routes (pre debugging)
     *
     * @return array<array{method: string, path: string, name: string}>
     */
    public function getRoutes(): array
    {
        return array_map(fn(array $route): array => [
            'method' => $route['method'],
            'path' => $route['path'],
            'name' => $route['name'],
        ], $this->routes);
    }
}
