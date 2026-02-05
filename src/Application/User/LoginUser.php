<?php

declare(strict_types=1);

namespace Blog\Application\User;

use Blog\Core\BaseUseCase;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Domain\User\ValueObject\Email;

final class LoginUser extends BaseUseCase
{
    public function __construct(
        private UserRepositoryInterface $users
    ) {
    }

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

        return $this->success([
            'user' => [
                'id' => $user->id()->toString(),
                'email' => $user->email()->toString(),
                'role' => $user->role()->toString(),
                'created_at' => $user->createdAt()->format('Y-m-d H:i:s'),
            ]
        ]);
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
        
        if (strlen($input['password']) < 8) {
            throw new \InvalidArgumentException('Password must be at least 8 characters long');
        }
    }
}
