<?php

declare(strict_types=1);

namespace Blog\Domain\User\Event;

use Blog\Domain\Common\DomainEvent;
use Blog\Domain\User\ValueObject\UserId;
use Blog\Domain\User\ValueObject\Email;
use DateTimeImmutable;

final readonly class UserRegisteredEvent implements DomainEvent
{
    private DateTimeImmutable $occurredOn;

    public function __construct(
        private UserId $userId,
        private Email $email
    ) {
        $this->occurredOn = new DateTimeImmutable();
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function eventName(): string
    {
        return 'user.registered';
    }
}
