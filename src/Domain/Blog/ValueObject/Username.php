<?php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

class Username
{
    private string $value;

    private function __construct(string $value)
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('Username cannot be empty');
        }

        if (strlen($value) < 3) {
            throw new \InvalidArgumentException('Username must be at least 3 characters long');
        }

        if (strlen($value) > 50) {
            throw new \InvalidArgumentException('Username cannot be longer than 50 characters');
        }

        $this->value = trim($value);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function equals(Username $other): bool
    {
        return $this->value === $other->value;
    }
}
