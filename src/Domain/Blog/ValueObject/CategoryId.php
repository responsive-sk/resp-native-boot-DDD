<?php
// src/Domain/Blog/ValueObject/CategoryId.php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

use Blog\Domain\Shared\ValueObject\IntValueObject;
use InvalidArgumentException;

final readonly class CategoryId extends IntValueObject
{
    protected function validate(): void
    {
        if ($this->value <= 0) {
            throw new InvalidArgumentException('Category ID must be positive');
        }
    }

    public static function fromString(string $value): static
    {
        if (!is_numeric($value)) {
            throw new InvalidArgumentException('Category ID must be numeric');
        }

        return new static((int)$value);
    }

    public static function fromInt(int $value): static
    {
        return new static($value);
    }
}
