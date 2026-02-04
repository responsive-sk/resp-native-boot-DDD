<?php

declare(strict_types=1);

namespace Blog\Domain\Audit\Repository;

use Blog\Domain\Audit\Entity\AuditLog;

interface AuditLogRepository
{
    public function save(AuditLog $log): void;

    /** @return AuditLog[] */
    public function findByUserId(string $userId, int $limit = 100): array;

    /** @return AuditLog[] */
    public function findByEventType(string $eventType, \DateTimeInterface $since = null): array;

    public function findFailedLogins(string $ipAddress, \DateTimeInterface $since = null): array;
}
