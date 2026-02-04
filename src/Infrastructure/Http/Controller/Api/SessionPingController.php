<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Api;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SessionPingController
{
    public function ping(ServerRequestInterface $request): ResponseInterface
    {
        // Middleware handled the session update. We just return OK.
        $response = new Response();
        $response->getBody()->write(json_encode([
            'status' => 'ok',
            'message' => 'Session refreshed',
            'expires_in' => 1800, // This could be dynamic based on config
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
