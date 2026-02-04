<?php

declare(strict_types=1);

namespace Blog\Domain\User\Entity;

use Blog\Domain\Common\DomainEvent;
use Blog\Domain\User\Event\UserRegisteredEvent;
use Blog\Domain\User\ValueObject\Email;
use Blog\Domain\User\ValueObject\HashedPassword;
use Blog\Domain\User\ValueObject\UserId;
use Blog\Domain\User\ValueObject\UserRole;
use DateTimeImmutable;

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
    public function id(): UserId
    {
        if ($this->id === null) {
            throw new \RuntimeException('User ID should not be null');
        }

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
