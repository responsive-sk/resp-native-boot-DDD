<?php
// src/Domain/Audit/ValueObject/AuditLogId.php
declare(strict_types=1);

namespace Blog\Domain\Audit\ValueObject;

use Blog\Domain\Shared\ValueObject\UuidValue;

final readonly class AuditLogId extends UuidValue
{
    public static function generate(): static
    {
        return new static(parent::generate()->toBytes());
    }
}
