<?php
// src/Domain/User/ValueObject/UserId.php
declare(strict_types=1);

namespace Blog\Domain\User\ValueObject;

use Blog\Domain\Shared\ValueObject\UuidValue;
use InvalidArgumentException;

final readonly class UserId extends UuidValue
{
    // Inherits all functionality from UuidValue
    // Can add user-specific validation if needed

    public static function generate(): static
    {
        return new static(parent::generate()->toBytes());
    }

    public static function fromString(string $uuidString): static
    {
        return parent::fromString($uuidString);
    }
}
