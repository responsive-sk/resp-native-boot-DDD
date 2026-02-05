<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

final readonly class TagName
{
    private string $value;

    private function __construct(string $value)
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('Názov tagu nemôže byť prázdny');
        }

        if (strlen($value) > 50) {
            throw new \InvalidArgumentException('Názov tagu môže mať maximálne 50 znakov');
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

    public function equals(TagName $other): bool
    {
        return strtolower($this->value) === strtolower($other->value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
