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
        private ?UserId           $id,
        private readonly Email    $email,
        private HashedPassword    $password,
        private UserRole          $role,
        private readonly DateTimeImmutable $createdAt
    ) {}

    public static function register(
        Email $email,
        HashedPassword $password,
        ?UserRole $role = null
    ): self {
        $user = new self(
            id: null,
            email: $email,
            password: $password,
            role: $role ?? UserRole::user(),
            createdAt: new DateTimeImmutable()
        );

        // Event will have null ID, we'll set it after saving
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

    public function verifyPassword(string $plainPassword): bool
    {
        return $this->password->verify($plainPassword);
    }

    public function changePassword(HashedPassword $newPassword): void
    {
        $this->password = $newPassword;
    }

    public function promoteToMark(): void
    {
        $this->role = UserRole::mark();
    }

    public function demoteToUser(): void
    {
        $this->role = UserRole::user();
    }

    // Getters
    public function id(): ?UserId
    {
        return $this->id;
    }
    public function email(): Email
    {
        return $this->email;
    }
    public function password(): HashedPassword
    {
        return $this->password;
    }
    public function role(): UserRole
    {
        return $this->role;
    }
    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    // For persistence layer to set ID after insert
    public function setId(UserId $id): void
    {
        if ($this->id !== null) {
            throw new LogicException('User ID has already been set');
        }
        $this->id = $id;

        // Now we can create event with ID
        $this->recordEvent(new UserRegisteredEvent($this->id, $this->email));
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
