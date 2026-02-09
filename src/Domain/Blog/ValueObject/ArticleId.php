<?php
// src/Domain/Blog/ValueObject/ArticleId.php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

use Blog\Domain\Shared\ValueObject\IntValueObject;
use InvalidArgumentException;

final readonly class ArticleId extends IntValueObject
{
    protected function validate(): void
    {
        if ($this->value <= 0) {
            throw new InvalidArgumentException('Article ID must be positive');
        }
    }

    public static function generate(): self
    {
        // For testing purposes, return a simple incrementing ID
        // In a real application, this would be handled by the database
        static $counter = 1;
        return new self($counter++);
    }

    // Add compatibility methods if needed
    public static function fromInt(int $value): self
    {
        return new self($value);
    }

    public static function fromString(string $value): self
    {
        if (!ctype_digit($value)) {
            throw new InvalidArgumentException('Article ID must be numeric string');
        }
        return new self((int) $value);
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function toString(): string
    {
        return (string) $this->value;
    }
}
