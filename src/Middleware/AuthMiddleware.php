<?php

declare(strict_types=1);

namespace Blog\Middleware;

use Blog\Security\Authorization;
use Blog\Security\Exception\AuthenticationException;
use Blog\Security\Exception\AuthorizationException;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest as ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Check if the request is an API request
     */
    private function isApiRequest(ServerRequestInterface $request): bool
    {
        $path = $request->getUri()->getPath();
        $acceptHeader = $request->getHeaderLine('Accept');
        
        // Check path prefix or Accept header
        return str_starts_with($path, '/api/') 
            || str_contains($acceptHeader, 'application/json')
            || str_contains($acceptHeader, 'application/vnd.api+json');
    }

    /**
     * Create JSON error response for API requests
     */
    private function createJsonErrorResponse(string $message, int $status = 401): ResponseInterface
    {
        $response = new Response($status, ['Content-Type' => 'application/json']);
        $response->getBody()->write(json_encode([
            'error' => $message,
            'message' => $message
        ]));
        
        return $response;
    }

    /**
     * Create redirect response for web requests
     */
    private function createRedirectResponse(string $location): ResponseInterface
    {
        return new Response(302, ['Location' => $location]);
    }

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
            // Return appropriate response based on request type
            if ($this->isApiRequest($request)) {
                return $this->createJsonErrorResponse('Authentication required', 401);
            }
            
            // Web request - redirect to login
            return $this->createRedirectResponse('/login');
        }

        // Kontrola ROLE_MARK pre /mark/*
        if (str_starts_with($path, '/mark')) {
            try {
                Authorization::requireMark();
            } catch (AuthorizationException $e) {
                // Return appropriate response based on request type
                if ($this->isApiRequest($request)) {
                    return $this->createJsonErrorResponse('MARK role required', 403);
                }
                
                // Web request - redirect to a default authorized page (e.g., blog index)
                return $this->createRedirectResponse('/blog');
            }
        }

        return $handler->handle($request);
    }
}
