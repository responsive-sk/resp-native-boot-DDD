<?php

declare(strict_types=1);

namespace Blog\Domain\Audit\Entity;

use Blog\Domain\Audit\ValueObject\AuditEventType;
use Blog\Domain\Audit\ValueObject\AuditLogId;
use DateTimeImmutable;

class AuditLog
{
    public function __construct(
        private AuditLogId $id,
        private AuditEventType $eventType,
        private string $description,
        private array $metadata,
        private ?string $userId = null,
        private ?string $userEmail = null,
        private ?string $ipAddress = null,
        private ?string $userAgent = null,
        private DateTimeImmutable $occurredAt = new DateTimeImmutable()
    ) {
    }

    public function id(): AuditLogId
    {
        return $this->id;
    }
    public function eventType(): AuditEventType
    {
        return $this->eventType;
    }
    public function description(): string
    {
        return $this->description;
    }
    public function metadata(): array
    {
        return $this->metadata;
    }
    public function userId(): ?string
    {
        return $this->userId;
    }
    public function userEmail(): ?string
    {
        return $this->userEmail;
    }
    public function ipAddress(): ?string
    {
        return $this->ipAddress;
    }
    public function userAgent(): ?string
    {
        return $this->userAgent;
    }
    public function occurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public static function createAuthenticationEvent(
        AuditLogId $id,
        string $eventType,
        string $email,
        bool $success,
        array $context = []
    ): self {
        return new self(
            $id,
            new AuditEventType($eventType),
            sprintf(
                '%s authentication attempt for %s',
                $success ? 'Successful' : 'Failed',
                $email
            ),
            array_merge(['success' => $success], $context),
            null,
            $email
        );
    }

    public static function createAuthorizationEvent(
        AuditLogId $id,
        string $eventType,
        ?string $userId,
        string $resource,
        bool $granted,
        array $context = []
    ): self {
        return new self(
            $id,
            new AuditEventType($eventType),
            sprintf(
                'Authorization %s for resource %s',
                $granted ? 'granted' : 'denied',
                $resource
            ),
            array_merge([
                'resource' => $resource,
                'granted' => $granted,
            ], $context),
            $userId
        );
    }
}
