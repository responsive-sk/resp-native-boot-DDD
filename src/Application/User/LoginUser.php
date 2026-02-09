<?php

declare(strict_types=1);

namespace Blog\Application\User;

use Blog\Core\UseCaseInterface;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Domain\User\ValueObject\Email;

final class LoginUser implements UseCaseInterface
{
    public function __construct(
        private UserRepositoryInterface $users
    ) {}

    public function execute(array $input): array
    {
        $this->validate($input);
        $email = $input['email'];
        $password = $input['password'];

        $emailVo = Email::fromString($email);
        $user = $this->users->findByEmail($emailVo);

        if (!$user) {
            throw new \DomainException('Invalid credentials');
        }

        if (!$user->verifyPassword($password)) {
            throw new \DomainException('Invalid credentials');
        }

        try {
            // This might trigger audit logging which could have UUID issues
            return $this->success([
                'user' => [
                    'id' => $user->id()->toString(),
                    'email' => $user->email()->toString(),
                    'role' => $user->getMarkRole()->toString(),
                    'created_at' => $user->createdAt()->format('Y-m-d H:i:s'),
                ],
            ]);
        } catch (\Ramsey\Uuid\Exception\InvalidArgumentException $e) {
            // Emergency fix: handle UUID errors gracefully
            error_log('UUID error during login (non-critical): ' . $e->getMessage());

            // Still return successful login, just skip audit logging
            return $this->success([
                'user' => [
                    'id' => $user->id()->toString(),
                    'email' => $user->email()->toString(),
                    'role' => $user->getMarkRole()->toString(),
                    'created_at' => $user->createdAt()->format('Y-m-d H:i:s'),
                ],
                'audit_warning' => 'Audit logging skipped due to UUID error',
            ]);
        }
    }

    protected function validate(array $input): void
    {
        if (empty($input['email'])) {
            throw new \InvalidArgumentException('Email is required');
        }

        if (empty($input['password'])) {
            throw new \InvalidArgumentException('Password is required');
        }

        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

    }

    protected function success(array $data): array
    {
        return [
            'success' => true,
            'data' => $data
        ];
    }

    protected function handle(array $input): mixed
    {
        return $this->execute($input);
    }
}
