<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Persistence\Doctrine;

use Blog\Domain\User\Entity\User;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Domain\User\ValueObject\UserId;
use Blog\Domain\User\ValueObject\Email;
use Blog\Domain\User\ValueObject\HashedPassword;
use Blog\Domain\User\ValueObject\UserRole;
use Doctrine\DBAL\Connection;
use DateTimeImmutable;

final readonly class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private Connection $connection
    ) {}

    public function findById(UserId $id): ?User
    {
        $row = $this->connection->fetchAssociative(
            'SELECT * FROM users WHERE id = ?',
            [$id->toInt()]
        );

        return $row ? $this->hydrate($row) : null;
    }

    public function findByEmail(Email $email): ?User
    {
        $row = $this->connection->fetchAssociative(
            'SELECT * FROM users WHERE email = ?',
            [$email->toString()]
        );

        return $row ? $this->hydrate($row) : null;
    }

    public function save(User $user): void
    {
        if ($user->id() === null) {
            $this->insert($user);
        } else {
            $this->update($user);
        }
    }

    public function emailExists(Email $email): bool
    {
        $count = $this->connection->fetchOne(
            'SELECT COUNT(*) FROM users WHERE email = ?',
            [$email->toString()]
        );

        return $count > 0;
    }

    private function insert(User $user): void
    {
        $this->connection->insert('users', [
            'email' => $user->email()->toString(),
            'password' => $user->password()->toString(),
            'role' => $user->role()->toString(),
            'created_at' => $user->createdAt()->format('Y-m-d H:i:s'),
        ]);

        $id = (int) $this->connection->lastInsertId();
        $user->setId(UserId::fromInt($id));
    }

    private function update(User $user): void
    {
        $this->connection->update('users', [
            'email' => $user->email()->toString(),
            'password' => $user->password()->toString(),
            'role' => $user->role()->toString(),
        ], [
            'id' => $user->id()->toInt(),
        ]);
    }

    private function hydrate(array $row): User
    {
        return User::reconstitute(
            UserId::fromInt((int) $row['id']),
            Email::fromString($row['email']),
            HashedPassword::fromHash($row['password']),
            UserRole::fromString($row['role']),
            new DateTimeImmutable($row['created_at'])
        );
    }
}
