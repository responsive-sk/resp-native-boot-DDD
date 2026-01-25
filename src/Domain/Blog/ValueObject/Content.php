<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

use InvalidArgumentException;

final readonly class Content
{
    private const MIN_LENGTH = 10;

    private function __construct(private string $value) {}

    public static function fromString(string $value): self
    {
        $trimmed = trim($value);

        if (empty($trimmed)) {
            throw new InvalidArgumentException('Obsah nemôže byť prázdny');
        }

        if (mb_strlen($trimmed) < self::MIN_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Obsah musí mať aspoň %d znakov', self::MIN_LENGTH)
            );
        }

        return new self($trimmed);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function excerpt(int $length = 200): string
    {
        if (mb_strlen($this->value) <= $length) {
            return $this->value;
        }

        return mb_substr($this->value, 0, $length) . '...';
    }

    public function wordCount(): int
    {
        return str_word_count($this->value);
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
