<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Response;

use Blog\Domain\User\Entity\User;

final readonly class UserResponse
{
    public function __construct(
        public string $id,
        public string $email,
        public string $role,
        public string $createdAt
    ) {
    }

    public static function fromEntity(User $user): self
    {
        return new self(
            id: $user->id()->toString(),
            email: $user->email()->toString(),
            role: $user->role()->toString(),
            createdAt: $user->createdAt()->format('c')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'role' => $this->role,
            'createdAt' => $this->createdAt,
        ];
    }
}
