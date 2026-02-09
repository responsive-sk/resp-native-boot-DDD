<?php
// src/Domain/Shared/ValueObject/IntValueObject.php
declare(strict_types=1);

namespace Blog\Domain\Shared\ValueObject;

abstract readonly class IntValueObject
{
    public function __construct(
        public int $value
    ) {
        $this->validate();
    }

    abstract protected function validate(): void;

    public function equals(self $other): bool
    {
        return static::class === $other::class && $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function isGreaterThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    public function isLessThan(self $other): bool
    {
        return $this->value < $other->value;
    }
}
