<?php

declare(strict_types=1);

namespace Blog\Middleware;

use Blog\Security\Authorization;
use Blog\Security\Exception\AuthenticationException;
use Blog\Security\Exception\AuthorizationException;
use Nyholm\Psr7\Response;
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
        try {
            Authorization::requireAuth();
        } catch (AuthenticationException $e) {
            // Redirect to login page
            return new Response(302, ['Location' => '/login']);
        }

        // Kontrola ROLE_MARK pre /mark/*
        if (str_starts_with($path, '/mark')) {
            try {
                Authorization::requireMark();
            } catch (AuthorizationException $e) {
                // Redirect to a default authorized page (e.g., blog index)
                return new Response(302, ['Location' => '/blog']);
            }
        }

        return $handler->handle($request);
    }
}
