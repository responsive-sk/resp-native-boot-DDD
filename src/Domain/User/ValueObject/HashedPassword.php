<?php

declare(strict_types=1);

namespace Blog\Domain\User\ValueObject;

use InvalidArgumentException;

final readonly class HashedPassword
{
    private function __construct(private string $hash)
    {
        if (empty($hash)) {
            throw new InvalidArgumentException('Hash hesla nemôže byť prázdny');
        }
    }

    public static function fromPlainPassword(string $plainPassword): self
    {
        if (strlen($plainPassword) < 6) {
            throw new InvalidArgumentException('Heslo musí mať aspoň 6 znakov');
        }

        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

        if ($hash === false) {
            throw new \RuntimeException('Nepodarilo sa zahašovať heslo');
        }

        return new self($hash);
    }

    public static function fromHash(string $hash): self
    {
        return new self($hash);
    }

    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hash);
    }

    public function toString(): string
    {
        return $this->hash;
    }

    public function __toString(): string
    {
        return $this->hash;
    }
}
