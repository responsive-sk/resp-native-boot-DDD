<?php

declare(strict_types=1);

namespace Blog\Middleware;

use Blog\Security\Authorization;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $path = $request->getUri()->getPath();

        // Pridaj user do requestu (môže byť null ak nie je prihlásený)
        $request = $request->withAttribute('user', Authorization::getUser());

        // Chránené cesty ktoré vyžadujú autentifikáciu
        $protectedPrefixes = [
            '/article/',  // CRUD operácie s článkami
            '/mark/',     // Mark admin panel
        ];

        // Kontroluj, či je to chránená cesta
        $isProtected = false;
        foreach ($protectedPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $isProtected = true;
                break;
            }
        }

        // Ak nie je chránená, pokračuj bez kontroly
        if (!$isProtected) {
            return $handler->handle($request);
        }

        // Pre chránené cesty - kontroluj autentifikáciu
        if ($redirect = Authorization::requireAuth()) {
            return $redirect;
        }

        // Kontrola ROLE_MARK pre /mark/*
        if (str_starts_with($path, '/mark') && $redirect = Authorization::requireMark()) {
            return $redirect;
        }

        return $handler->handle($request);
    }
}
