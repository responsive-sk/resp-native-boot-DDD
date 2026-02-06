<?php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

class AuthorName
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('Author name cannot be empty');
        }

        if (strlen($value) > 255) {
            throw new \InvalidArgumentException('Author name cannot be longer than 255 characters');
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

    public function equals(AuthorName $other): bool
    {
        return $this->value === $other->value;
    }
}
