<?php

declare(strict_types=1);

namespace Blog\Domain\Common;

use DateTimeImmutable;

interface DomainEvent
{
    public function occurredOn(): DateTimeImmutable;
    public function eventName(): string;
}
