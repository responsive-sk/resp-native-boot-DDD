<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Api;

use Blog\Application\User\LoginUser;
use Blog\Application\User\RegisterUser;
use Blog\Domain\User\ValueObject\Email;
use Blog\Domain\User\ValueObject\HashedPassword;
use Blog\Domain\User\ValueObject\UserRole;
use Blog\Infrastructure\Http\Response\UserResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;

final class AuthApiController
{
    public function __construct(
        private LoginUser $loginUser,
        private RegisterUser $registerUser
    ) {}

    public function register(ServerRequestInterface $request): ResponseInterface
    {
        $data = json_decode((string) $request->getBody(), true);

        try {
            $email = new Email($data['email'] ?? '');
            $password = HashedPassword::fromPlainPassword($data['password'] ?? '');
            $role = isset($data['role']) ? UserRole::fromString($data['role']) : null;

            $user = $this->registerUser->execute($email, $password, $role);

            return $this->jsonResponse([
                'message' => 'User registered successfully',
                'user' => UserResponse::fromEntity($user)->toArray(),
            ], 201);
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function login(ServerRequestInterface $request): ResponseInterface
    {
        $data = json_decode((string) $request->getBody(), true);

        try {
            $email = new Email($data['email'] ?? '');
            $password = $data['password'] ?? '';

            $user = $this->loginUser->execute($email, $password);

            // V reálnej aplikácii by tu bol JWT token
            return $this->jsonResponse([
                'message' => 'Login successful',
                'user' => UserResponse::fromEntity($user)->toArray(),
                'token' => 'mock-token-for-user-' . $user->id()->toInt(),
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 401);
        }
    }

    private function jsonResponse(array $data, int $status = 200): ResponseInterface
    {
        return new Response(
            $status,
            ['Content-Type' => 'application/json'],
            json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
        );
    }
}