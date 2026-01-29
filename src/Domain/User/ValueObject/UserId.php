<?php

declare(strict_types=1);

namespace Blog\Domain\User\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class UserId
{
    private function __construct(private UuidInterface $uuid)
    {
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $id): self
    {
        if (!Uuid::isValid($id)) {
            throw new InvalidArgumentException(sprintf('Invalid User UUID: %s', $id));
        }

        return new self(Uuid::fromString($id));
    }

    public static function fromBytes(string $bytes): self
    {
        return new self(Uuid::fromBytes($bytes));
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public function toBytes(): string
    {
        return $this->uuid->getBytes();
    }

    public function equals(self $other): bool
    {
        return $this->uuid->equals($other->uuid);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
