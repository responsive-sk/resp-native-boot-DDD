<?php

declare(strict_types=1);

namespace Blog\Domain\User\ValueObject;

use InvalidArgumentException;

final readonly class Email
{
    private function __construct(private string $value) {}

    /**
     * @throws InvalidArgumentException
     */
    public static function fromString(string $value): self
    {
        $trimmed = trim($value);

        if ($trimmed === '') {
            throw new InvalidArgumentException('Email nemôže byť prázdny');
        }

        if (!filter_var($trimmed, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                sprintf('Neplatný email formát: "%s"', $trimmed)
            );
        }

        // Normalizácia – lowercase + odstránenie zbytočných medzier
        return new self(strtolower($trimmed));
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function domain(): string
    {
        return substr($this->value, strpos($this->value, '@') + 1);
    }

    public function localPart(): string
    {
        return substr($this->value, 0, strpos($this->value, '@'));
    }

    public function __toString(): string
    {
        return $this->value;
    }
}