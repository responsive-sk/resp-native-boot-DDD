<?php

declare(strict_types=1);

namespace Blog\Domain\User\Entity;

use Blog\Domain\Common\DomainEvent;
use Blog\Domain\User\ValueObject\UserId;
use Blog\Domain\User\ValueObject\Email;
use Blog\Domain\User\ValueObject\HashedPassword;
use Blog\Domain\User\ValueObject\UserRole;
use Blog\Domain\User\Event\UserRegisteredEvent;
use DateTimeImmutable;
use LogicException;

final class User
{
    /** @var array<int, DomainEvent> */
    private array $domainEvents = [];

    private function __construct(
        private ?UserId $id,
        private readonly Email $email,
        private HashedPassword $password,
        private UserRole $role,
        private readonly DateTimeImmutable $createdAt
    ) {
    }

    public static function register(
        Email $email,
        HashedPassword $password,
        ?UserRole $role = null
    ): self {
        $id = UserId::generate();
        $user = new self(
            id: $id,
            email: $email,
            password: $password,
            role: $role ?? UserRole::user(),
            createdAt: new DateTimeImmutable()
        );

        $user->recordEvent(new UserRegisteredEvent($id, $email));

        return $user;
    }

    public static function reconstitute(
        UserId $id,
        Email $email,
        HashedPassword $password,
        UserRole $role,
        DateTimeImmutable $createdAt
    ): self {
        return new self($id, $email, $password, $role, $createdAt);
    }


    // Domain Events
    private function recordEvent(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }

    /**
     * @return array<int, DomainEvent>
     */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];
        return $events;
    }
}
