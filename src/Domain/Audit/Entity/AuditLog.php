<?php

declare(strict_types=1);

namespace Blog\Domain\Audit\Entity;

use Blog\Domain\Audit\ValueObject\AuditEventType;
use Blog\Domain\Audit\ValueObject\AuditLogId;
use DateTimeImmutable;

class AuditLog
{
    private AuditLogId $id;
    private ?string $userId;
    private AuditEventType $eventType;
    private ?string $ipAddress;
    private ?string $userAgent;
    private array $metadata;
    private DateTimeImmutable $createdAt;

    public function __construct(
        AuditLogId $id,
        ?string $userId,
        AuditEventType $eventType,
        ?string $ipAddress,
        ?string $userAgent,
        array $metadata,
        DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->eventType = $eventType;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->metadata = $metadata;
        $this->createdAt = $createdAt;
    }

    public static function create(
        AuditEventType $eventType,
        ?string $userId,
        ?string $ipAddress,
        ?string $userAgent,
        array $metadata = []
    ): self {
        return new self(
            AuditLogId::fromString(uniqid('audit_', true)),
            $userId,
            $eventType,
            $ipAddress,
            $userAgent,
            $metadata,
            new DateTimeImmutable()
        );
    }

    public static function createAuthenticationEvent(
        string $email,
        AuditEventType $eventType,
        bool $success,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        array $metadata = []
    ): self {
        return new self(
            AuditLogId::fromString(uniqid('audit_', true)),
            $email,
            $eventType,
            $ipAddress,
            $userAgent,
            array_merge($metadata, [
                'success' => $success,
            ]),
            new DateTimeImmutable()
        );
    }

    public static function createAuthorizationEvent(
        ?string $userId,
        AuditEventType $eventType,
        string $resource,
        bool $granted,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        array $metadata = []
    ): self {
        return new self(
            AuditLogId::fromString(uniqid('audit_', true)),
            $userId,
            $eventType,
            $ipAddress,
            $userAgent,
            array_merge($metadata, [
                'resource' => $resource,
                'granted' => $granted,
            ]),
            new DateTimeImmutable()
        );
    }

    public static function createArticleEvent(
        AuditEventType $eventType,
        string $articleId,
        ?string $userId,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        array $metadata = []
    ): self {
        return new self(
            AuditLogId::fromString(uniqid('audit_', true)),
            $userId,
            $eventType,
            $ipAddress,
            $userAgent,
            array_merge($metadata, [
                'article_id' => $articleId,
            ]),
            new DateTimeImmutable()
        );
    }

    public static function reconstitute(
        AuditLogId $id,
        ?string $userId,
        AuditEventType $eventType,
        ?string $ipAddress,
        ?string $userAgent,
        array $metadata,
        DateTimeImmutable $createdAt
    ): self {
        return new self(
            $id,
            $userId,
            $eventType,
            $ipAddress,
            $userAgent,
            $metadata,
            $createdAt
        );
    }

    public function getId(): AuditLogId
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getEventType(): AuditEventType
    {
        return $this->eventType;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }


    public function getEventDescription(): string
    {
        $descriptions = AuditEventType::getAllTypes();
        return $descriptions[$this->eventType->value()] ?? $this->eventType->value();
    }

    public function getUsername(): ?string
    {
        return $this->metadata['username'] ?? $this->userId ?? 'Unknown';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'user_id' => $this->userId,
            'event_type' => $this->eventType->value(),
            'event_description' => $this->getEventDescription(),
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'metadata' => $this->metadata,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
