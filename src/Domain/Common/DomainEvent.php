<?php
// src/Domain/Common/DomainEvent.php
declare(strict_types=1);

namespace Blog\Domain\Common;

use DateTimeImmutable;

interface DomainEvent
{
    public function occurredOn(): DateTimeImmutable;
    public function getAggregateId(): string;
    public function getEventName(): string;
    public function toArray(): array;
}
