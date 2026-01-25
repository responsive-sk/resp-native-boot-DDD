<?php

declare(strict_types=1);

namespace Blog\Middleware;

use Blog\Security\Authorization;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Nyholm\Psr7\Response;

final class AuthMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $path = $request->getUri()->getPath();

        // Add user to request (may be null if not logged in)
        $request = $request->withAttribute('user', Authorization::getUser());

        // Protected paths that require authentication
        $protectedPrefixes = [
            '/article/',  // CRUD operácie s článkami
            '/mark/',     // Mark admin panel
        ];

        // Check if this is a protected path
        $isProtected = false;
        foreach ($protectedPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $isProtected = true;
                break;
            }
        }

        // If not protected, continue without checks
        if (!$isProtected) {
            return $handler->handle($request);
        }

        // For protected paths - check authentication
        if ($redirect = Authorization::requireAuth()) {
            return $redirect;
        }

        // Check ROLE_MARK for /mark/*
        if (str_starts_with($path, '/mark') && $redirect = Authorization::requireMark()) {
            return $redirect;
        }

        return $handler->handle($request);
    }
}