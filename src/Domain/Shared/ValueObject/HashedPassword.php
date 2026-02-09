<?php
// src/Domain/Shared/ValueObject/HashedPassword.php
declare(strict_types=1);

namespace Blog\Domain\Shared\ValueObject;

final readonly class HashedPassword
{
    public function __construct(
        private string $value
    ) {
    }

    public static function hash(string $plainPassword): self
    {
        return new self(password_hash($plainPassword, PASSWORD_DEFAULT));
    }

    public static function fromHash(string $hash): self
    {
        return new self($hash);
    }

    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
