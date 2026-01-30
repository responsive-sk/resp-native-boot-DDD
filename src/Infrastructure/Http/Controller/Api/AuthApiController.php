<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Api;

use Blog\Application\User\LoginUser;
use Blog\Application\User\RegisterUser;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Domain\User\ValueObject\Email;
use Blog\Domain\User\ValueObject\HashedPassword;
use Blog\Domain\User\ValueObject\UserRole;
use Blog\Infrastructure\Http\Response\UserResponse;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AuthApiController
{
    public function __construct(
        private LoginUser $loginUser,
        private RegisterUser $registerUser,
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function register(ServerRequestInterface $request): ResponseInterface
    {
        $data = json_decode((string) $request->getBody(), true);

        try {
            $email = Email::fromString($data['email'] ?? '');
            $password = HashedPassword::fromPlainPassword($data['password'] ?? '');
            $role = isset($data['role']) ? UserRole::fromString($data['role']) : null;

            $userId = ($this->registerUser)($email->toString(), $password->toString());

            // Fetch the full User entity after registration
            $userEntity = $this->userRepository->findById($userId);

            if (!$userEntity) {
                // This should theoretically not happen if registration was successful
                throw new \RuntimeException('Registered user not found.');
            }

            return $this->jsonResponse([
                'message' => 'User registered successfully',
                'user' => UserResponse::fromEntity($userEntity)->toArray(),
            ], 201);
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function login(ServerRequestInterface $request): ResponseInterface
    {
        $data = json_decode((string) $request->getBody(), true);

        try {
            $email = Email::fromString($data['email'] ?? '');
            $password = $data['password'] ?? '';

            $user = ($this->loginUser)($email->toString(), $password);

            // V reálnej aplikácii by tu bol JWT token
            return $this->jsonResponse([
                'message' => 'Login successful',
                'user' => UserResponse::fromEntity($user)->toArray(),
                'token' => 'mock-token-for-user-' . $user->id()->toString(),
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
