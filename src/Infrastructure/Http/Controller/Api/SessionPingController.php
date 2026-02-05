<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Api;

use Blog\Infrastructure\Http\Controller\BaseController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SessionPingController extends BaseController
{
    public function ping(ServerRequestInterface $request): ResponseInterface
    {
        // Middleware handled the session update. We just return OK.
        return $this->jsonResponse([
            'status' => 'ok',
            'message' => 'Session refreshed',
            'expires_in' => 1800, // This could be dynamic based on config
        ]);
    }
}
