<?php

declare(strict_types=1);

namespace Blog\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * RouterMiddleware - middleware ktorý matchuje route a volá handler
 */
final readonly class RouterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Router $router
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Nájdi matching route
        $routeMatch = $this->router->match($request);

        // Pridaj route parametre do request atribútov
        foreach ($routeMatch->params as $name => $value) { // getParams() -> params
            $request = $request->withAttribute($name, $value);
        }

        // Pridaj route name do request atribútov (pre templating)
        $request = $request->withAttribute('_route', $routeMatch->name); // getName() -> name

        // Zavolaj route handler
        $routeHandler = $routeMatch->handler; // getHandler() -> handler

        return $routeHandler($request);
    }
}
