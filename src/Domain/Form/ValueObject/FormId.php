<?php

declare(strict_types=1);

namespace Blog\Domain\Form\ValueObject;

use InvalidArgumentException;

final readonly class FormId
{
    private string $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromString(string $value): self
    {
        if (empty($value)) {
            throw new InvalidArgumentException("Form ID cannot be empty.");
        }
        
        return new self($value);
    }

    public function toString(): string
    {
        return $this->id;
    }

    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
