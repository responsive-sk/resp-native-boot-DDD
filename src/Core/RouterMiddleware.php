<?php

declare(strict_types=1);

namespace Blog\Core;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * RouterMiddleware - middleware ktorý matchuje route a volá handler
 * 
 * Nahradza Chubbyphp\Framework\Middleware\RouteMatcherMiddleware
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
        foreach ($routeMatch->getParams() as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        // Pridaj route name do request atribútov (pre templating)
        $request = $request->withAttribute('_route', $routeMatch->getName());

        // Zavolaj route handler
        $routeHandler = $routeMatch->getHandler();
        return $routeHandler($request);
    }
}

