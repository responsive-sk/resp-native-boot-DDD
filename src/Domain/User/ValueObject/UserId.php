<?php

declare(strict_types=1);

namespace Blog\Domain\User\ValueObject;

use InvalidArgumentException;

final readonly class UserId
{
    private function __construct(private int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('User ID musí byť kladné číslo');
        }
    }

    public static function fromInt(int $value): self
    {
        return new self($value);
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
