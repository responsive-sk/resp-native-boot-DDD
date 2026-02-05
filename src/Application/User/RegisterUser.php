<?php

declare(strict_types=1);

namespace Blog\Application\User;

use Blog\Core\BaseUseCase;
use Blog\Domain\User\Entity\User;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Domain\User\ValueObject\Email;
use Blog\Domain\User\ValueObject\HashedPassword;

final class RegisterUser extends BaseUseCase
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
        
        // Always create as ROLE_USER - role assignment is admin-only
        $role = 'ROLE_USER';

        // 1. Validate email
        $emailVo = Email::fromString($email);

        // 2. Check if user exists
        if ($this->users->findByEmail($emailVo) !== null) {
            throw new \DomainException('User already exists');
        }

        // 3. Create user
        $user = User::register(
            $emailVo,
            HashedPassword::fromPlainPassword($password)
        );

        // 4. Persist
        $this->users->save($user);

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
        
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $input['password'])) {
            throw new \InvalidArgumentException('Password must contain at least one uppercase letter, one lowercase letter, and one number');
        }
    }
}
