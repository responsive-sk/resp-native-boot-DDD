<?php

declare(strict_types=1);

namespace Blog\Domain\Audit\Repository;

use Blog\Domain\Audit\Entity\AuditLog;
use Blog\Domain\Audit\ValueObject\AuditLogId;

interface AuditLogRepository
{
    public function save(AuditLog $log): void;

    /** @return AuditLog[] */
    public function findByUserId(string $userId, ?int $limit = null): array;

    /** @return AuditLog[] */
    public function findByEventType(string $eventType, ?int $limit = null): array;

    public function find(AuditLogId $id): ?AuditLog;

    /** @return AuditLog[] */
    public function getRecentLogs(int $limit = 100): array;

    /** @return array{total: int, by_event: array, by_day: array} */
    public function getStatistics(array $filters = []): array;

    public function count(array $filters = []): int;
}
