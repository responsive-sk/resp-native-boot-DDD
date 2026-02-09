<?php
// src/Domain/Blog/ValueObject/Title.php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

use Blog\Domain\Shared\ValueObject\StringValueObject;
use InvalidArgumentException;

final readonly class Title extends StringValueObject
{
    protected function validate(): void
    {
        if (empty($this->value)) {
            throw new InvalidArgumentException('Title cannot be empty');
        }

        if (strlen($this->value) > 255) {
            throw new InvalidArgumentException('Title cannot exceed 255 characters');
        }
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
