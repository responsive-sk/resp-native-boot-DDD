<?php
// src/Domain/Shared/ValueObject/StringValueObject.php
declare(strict_types=1);

namespace Blog\Domain\Shared\ValueObject;

abstract readonly class StringValueObject
{
    public function __construct(
        public string $value
    ) {
        $this->validate();
    }

    abstract protected function validate(): void;

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return static::class === $other::class && $this->value === $other->value;
    }
}
