<?php

declare(strict_types=1);

namespace Blog\Domain\User\ValueObject;

use InvalidArgumentException;

final readonly class UserRole
{
    private const ROLE_USER = 'ROLE_USER';
    private const ROLE_MARK = 'ROLE_MARK';

    private const VALID_ROLES = [
        self::ROLE_USER,
        self::ROLE_MARK,
    ];

    private function __construct(private string $value)
    {
        if (!in_array($value, self::VALID_ROLES, true)) {
            throw new InvalidArgumentException(
                sprintf('Neplatná rola: %s', $value)
            );
        }
    }

    public static function user(): self
    {
        return new self(self::ROLE_USER);
    }

    public static function mark(): self
    {
        return new self(self::ROLE_MARK);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function isMark(): bool
    {
        return $this->value === self::ROLE_MARK;
    }

    public function isUser(): bool
    {
        return $this->value === self::ROLE_USER;
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
