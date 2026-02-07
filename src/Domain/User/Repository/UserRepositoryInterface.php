<?php

declare(strict_types=1);

namespace Blog\Domain\User\Repository;

use Blog\Domain\User\Entity\User;
use Blog\Domain\User\ValueObject\Email;
use Blog\Domain\User\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function findById(UserId $id): ?User;

    public function findByEmail(Email $email): ?User;

    public function save(User $user): void;

    public function emailExists(Email $email): bool;

    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @return User[]
     */
    public function getRecentUsers(int $limit = 10): array;

    public function count(array $filters = []): int;

    public function remove(UserId $id): void;
}
