<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

use InvalidArgumentException;

final readonly class Title
{
    private const MIN_LENGTH = 3;
    private const MAX_LENGTH = 255;

    private function __construct(private string $value) {}

    public static function fromString(string $value): self
    {
        $trimmed = trim($value);

        if (empty($trimmed)) {
            throw new InvalidArgumentException('Titulok nemôže byť prázdny');
        }

        if (mb_strlen($trimmed) < self::MIN_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Titulok musí mať aspoň %d znaky', self::MIN_LENGTH)
            );
        }

        if (mb_strlen($trimmed) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Titulok môže mať maximálne %d znakov', self::MAX_LENGTH)
            );
        }

        return new self($trimmed);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
