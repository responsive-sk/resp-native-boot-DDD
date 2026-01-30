<?php

declare(strict_types=1);

namespace Blog\Application\User;

use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Domain\User\ValueObject\Email;

final class LoginUser
{
    public function __construct(
        private UserRepositoryInterface $users
    ) {
    }

    public function __invoke(string $email, string $password): \Blog\Domain\User\Entity\User
    {
        $emailVo = Email::fromString($email);
        $user = $this->users->findByEmail($emailVo);

        if (!$user) {
            throw new \DomainException('Invalid credentials');
        }

        if (!$user->verifyPassword($password)) {
            throw new \DomainException('Invalid credentials');
        }

        return $user;  // ← Vráti User objekt!
    }
}
