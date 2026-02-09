<?php
// src/Domain/Shared/ValueObject/UuidValue.php
declare(strict_types=1);

namespace Blog\Domain\Shared\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Stringable;

readonly class UuidValue implements Stringable
{
    public function __construct(
        protected string $value
    ) {
        $this->validate();
    }

    protected function validate(): void
    {
        // Accept both binary (16 byte) and string (36 char) representations
        $length = strlen($this->value);

        if ($length !== 16 && $length !== 36) {
            throw new InvalidArgumentException(
                sprintf('UUID must be 16 bytes (binary) or 36 chars (string), got %d bytes. Value: %s', $length, bin2hex($this->value))
            );
        }

        // If it's a string representation, validate UUID format
        if ($length === 36 && !Uuid::isValid($this->value)) {
            throw new InvalidArgumentException("Invalid UUID string format: {$this->value}");
        }

        // If it's binary, we can't validate format easily but trust it's valid if 16 bytes
    }

    public static function generate(): static
    {
        return new static(Uuid::uuid4()->getBytes());
    }

    public static function fromString(string $uuidString): static
    {
        if (!Uuid::isValid($uuidString)) {
            throw new InvalidArgumentException("Invalid UUID string: $uuidString");
        }

        return new static(Uuid::fromString($uuidString)->getBytes());
    }

    public static function fromBytes(string $binaryUuid): static
    {
        return new static($binaryUuid);
    }

    public function toString(): string
    {
        if (strlen($this->value) === 16) {
            // Convert binary to string
            return Uuid::fromBytes($this->value)->toString();
        }

        return $this->value;
    }

    public function toBytes(): string
    {
        if (strlen($this->value) === 36) {
            // Convert string to binary
            return Uuid::fromString($this->value)->getBytes();
        }

        return $this->value;
    }

    public function toUuid(): UuidInterface
    {
        if (strlen($this->value) === 16) {
            return Uuid::fromBytes($this->value);
        }

        return Uuid::fromString($this->value);
    }

    public function equals(self $other): bool
    {
        // Compare binary representations
        return $this->toBytes() === $other->toBytes();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function getBinary(): string
    {
        return $this->toBytes();
    }

    public function getHex(): string
    {
        return bin2hex($this->toBytes());
    }
}
