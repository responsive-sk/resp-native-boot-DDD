<?php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

class AuthorId
{
    private string $value;

    private function __construct(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Author ID cannot be empty');
        }

        $this->value = $value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function generate(): self
    {
        return new self(\Ramsey\Uuid\Uuid::uuid4()->toString());
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function equals(AuthorId $other): bool
    {
        return $this->value === $other->value;
    }
}
