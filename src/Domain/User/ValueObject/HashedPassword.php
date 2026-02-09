<?php

declare(strict_types=1);

namespace Blog\Domain\User\ValueObject;

use InvalidArgumentException;

final readonly class HashedPassword
{
    private function __construct(
        private string $hash
    ) {
        if (empty($hash)) {
            throw new InvalidArgumentException('Hash hesla nemôže byť prázdny');
        }
    }

    public static function fromPlainPassword(string $plainPassword, array $config): self
    {
        $min_length = $config['min_length'] ?? 8;
        $require_uppercase = $config['require_uppercase'] ?? false;
        $require_lowercase = $config['require_lowercase'] ?? false;
        $require_number = $config['require_number'] ?? false;
        $require_special_char = $config['require_special_char'] ?? false;

        if (strlen($plainPassword) < $min_length) {
            throw new InvalidArgumentException(sprintf('Heslo musí mať aspoň %d znakov', $min_length));
        }

        if ($require_uppercase && !preg_match('/[A-Z]/', $plainPassword)) {
            throw new InvalidArgumentException('Heslo musí obsahovať aspoň jedno veľké písmeno');
        }

        if ($require_lowercase && !preg_match('/[a-z]/', $plainPassword)) {
            throw new InvalidArgumentException('Heslo musí obsahovať aspoň jedno malé písmeno');
        }

        if ($require_number && !preg_match('/[0-9]/', $plainPassword)) {
            throw new InvalidArgumentException('Heslo musí obsahovať aspoň jedno číslo');
        }

        if ($require_special_char && !preg_match('/[^a-zA-Z0-9]/', $plainPassword)) {
            throw new InvalidArgumentException('Heslo musí obsahovať aspoň jeden špeciálny znak');
        }

        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

        return new self($hash);
    }

    public static function fromHash(string $hash, array $config): self
    {
        return new self($hash);
    }

    public static function hash(string $plainPassword): self
    {
        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);
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
