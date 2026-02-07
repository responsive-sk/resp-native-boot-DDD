<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Api;

use Blog\Application\User\LoginUser;
use Blog\Application\User\RegisterUser;
use Blog\Infrastructure\Http\Controller\BaseController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AuthApiController extends BaseController
{
    public function register(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(RegisterUser::class);

        try {
            $result = $this->executeUseCase($request, $useCase, [
                'email' => 'body:email',
                'password' => 'body:password',
                'role' => 'body:role',
            ], 'api');

            return $this->jsonResponse([
                'message' => 'User registered successfully',
                'user' => $result['user'],
            ], 201);
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function login(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(LoginUser::class);

        try {
            $result = $this->executeUseCase($request, $useCase, [
                'email' => 'body:email',
                'password' => 'body:password',
            ], 'api');

            // V reálnej aplikácii by tu bol JWT token
            return $this->jsonResponse([
                'message' => 'Login successful',
                'user' => $result['user'],
                'token' => 'mock-token-for-user-' . $result['user']['id'],
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 401);
        }
    }
}
