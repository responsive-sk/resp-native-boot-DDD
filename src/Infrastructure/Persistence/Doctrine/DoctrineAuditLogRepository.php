<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Persistence\Doctrine;

use Blog\Domain\Audit\Entity\AuditLog;
use Blog\Domain\Audit\Repository\AuditLogRepository;
use Blog\Domain\Audit\ValueObject\AuditLogId;
use Doctrine\DBAL\Connection;

class DoctrineAuditLogRepository implements AuditLogRepository
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function save(AuditLog $log): void
    {
        $this->connection->insert('audit_logs', [
            'id' => $log->id()->toString(),
            'event_type' => $log->eventType()->value(),
            'description' => $log->description(),
            'metadata' => json_encode($log->metadata()),
            'user_id' => $log->userId(),
            'user_email' => $log->userEmail(),
            'ip_address' => $log->ipAddress(),
            'user_agent' => $log->userAgent(),
            'occurred_at' => $log->occurredAt()->format('Y-m-d H:i:s'),
        ]);
    }

    public function findByUserId(string $userId, int $limit = 100): array
    {
        $sql = "SELECT * FROM audit_logs WHERE user_id = ? ORDER BY occurred_at DESC LIMIT ?";
        $rows = $this->connection->fetchAllAssociative($sql, [$userId, $limit]);

        return array_map([$this, 'hydrateAuditLog'], $rows);
    }

    public function findByEventType(string $eventType, ?\DateTimeInterface $since = null): array
    {
        $sql = "SELECT * FROM audit_logs WHERE event_type = ?";
        $params = [$eventType];

        if ($since) {
            $sql .= " AND occurred_at >= ?";
            $params[] = $since->format('Y-m-d H:i:s');
        }

        $sql .= " ORDER BY occurred_at DESC";
        $rows = $this->connection->fetchAllAssociative($sql, $params);

        return array_map([$this, 'hydrateAuditLog'], $rows);
    }

    public function findFailedLogins(string $ipAddress, ?\DateTimeInterface $since = null): array
    {
        $sql = "SELECT * FROM audit_logs 
                WHERE event_type = 'login_failed' 
                AND (ip_address = ? OR metadata LIKE ?)";
        $params = [$ipAddress, '%"' . $ipAddress . '"%'];

        if ($since) {
            $sql .= " AND occurred_at >= ?";
            $params[] = $since->format('Y-m-d H:i:s');
        }

        $sql .= " ORDER BY occurred_at DESC";
        $rows = $this->connection->fetchAllAssociative($sql, $params);

        return array_map([$this, 'hydrateAuditLog'], $rows);
    }

    private function hydrateAuditLog(array $row): AuditLog
    {
        return new AuditLog(
            AuditLogId::fromString($row['id']),
            new \Blog\Domain\Audit\ValueObject\AuditEventType($row['event_type']),
            $row['description'],
            json_decode($row['metadata'], true),
            $row['user_id'],
            $row['user_email'],
            $row['ip_address'],
            $row['user_agent'],
            new \DateTimeImmutable($row['occurred_at'])
        );
    }
}
