<?php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

class Email
{
    private string $value;

    private function __construct(string $value)
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('Email cannot be empty');
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        $this->value = strtolower(trim($value));
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

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }
}
