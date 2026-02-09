<?php
// src/Domain/Blog/ValueObject/CategoryName.php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

use Blog\Domain\Shared\ValueObject\StringValueObject;
use InvalidArgumentException;

final readonly class CategoryName extends StringValueObject
{
    protected function validate(): void
    {
        if (empty(trim($this->value))) {
            throw new InvalidArgumentException('Category name cannot be empty');
        }

        if (strlen($this->value) > 50) {
            throw new InvalidArgumentException('Category name cannot exceed 50 characters');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
