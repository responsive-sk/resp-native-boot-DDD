<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Persistence\Doctrine;

use Blog\Domain\User\Entity\User;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Domain\User\ValueObject\Email;
use Blog\Domain\User\ValueObject\HashedPassword;
use Blog\Domain\User\ValueObject\UserId;
use Blog\Domain\User\ValueObject\UserRole;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

final readonly class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function findById(UserId $id): ?User
    {
        $row = $this->connection->fetchAssociative(
            'SELECT * FROM users WHERE id = ?',
            [$id->toBytes()]
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

    public function findAll(): array
    {
        $rows = $this->connection->fetchAllAssociative('SELECT * FROM users ORDER BY created_at DESC');

        $users = [];

        foreach ($rows as $row) {
            $users[] = $this->hydrate($row);
        }

        return $users;
    }

    public function save(User $user): void
    {
        // Simple check: if exists update, else insert
        // Since we generate UUIDs in the domain, we might need to check existence if we are not sure
        // But typically for 'save' in DDD, we can use UPSERT or check if we loaded it.
        // For simplicity: check if exists by ID.

        $exists = $this->connection->fetchOne('SELECT 1 FROM users WHERE id = ?', [$user->id()->toBytes()]);

        if ($exists) {
            $this->update($user);
        } else {
            $this->insert($user);
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

    public function getRecentUsers(int $limit = 10): array
    {
        $rows = $this->connection->fetchAllAssociative(
            'SELECT * FROM users ORDER BY created_at DESC LIMIT ?',
            [$limit],
            [ParameterType::INTEGER]
        );

        return array_map([$this, 'hydrate'], $rows);
    }

    public function count(array $filters = []): int
    {
        $where = [];
        $params = [];
        $types = [];
        
        if (!empty($filters['role'])) {
            $where[] = 'role = ?';
            $params[] = $filters['role'];
            $types[] = ParameterType::STRING;
        }
        
        if (!empty($filters['start_date'])) {
            $where[] = 'created_at >= ?';
            $params[] = $filters['start_date'];
            $types[] = ParameterType::STRING;
        }
        
        if (!empty($filters['end_date'])) {
            $where[] = 'created_at <= ?';
            $params[] = $filters['end_date'];
            $types[] = ParameterType::STRING;
        }
        
        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $sql = "SELECT COUNT(*) FROM users {$whereClause}";
        
        return (int)$this->connection->fetchOne($sql, $params, $types);
    }

    public function remove(UserId $id): void
    {
        $this->connection->delete('users', ['id' => $id->toBytes()]);
    }

    private function insert(User $user): void
    {
        $this->connection->insert('users', [
            'id' => $user->id()->toBytes(),
            'email' => $user->email()->toString(),
            'password_hash' => $user->password()->toString(),
            'role' => $user->role()->toString(),
            'created_at' => $user->createdAt()->format('Y-m-d H:i:s'),
        ]);
    }

    private function update(User $user): void
    {
        $this->connection->update('users', [
            'email' => $user->email()->toString(),
            'password_hash' => $user->password()->toString(),
            'role' => $user->role()->toString(),
        ], [
            'id' => $user->id()->toBytes(),
        ]);
    }

    private function hydrate(array $row): User
    {
        return User::reconstitute(
            UserId::fromBytes($row['id']),
            Email::fromString($row['email']),
            HashedPassword::fromHash($row['password_hash']),
            UserRole::fromString($row['role']),
            new DateTimeImmutable($row['created_at'])
        );
    }
}
