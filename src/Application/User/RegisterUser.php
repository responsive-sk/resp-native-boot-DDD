<?php

declare(strict_types=1);

namespace Blog\Application\User;

use Blog\Domain\User\Entity\User;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Domain\User\ValueObject\Email;
use Blog\Domain\User\ValueObject\HashedPassword;

final class RegisterUser
{
    public function __construct(
        private UserRepositoryInterface $users
    ) {
    }

    public function __invoke(string $email, string $password): User
    {
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

        // Vrátiť celú User entitu
        return $user;
    }
}
