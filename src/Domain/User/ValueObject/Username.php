<?php
// src/Domain/User/ValueObject/Username.php
declare(strict_types=1);

namespace Blog\Domain\User\ValueObject;

use Blog\Domain\Shared\ValueObject\StringValueObject;
use InvalidArgumentException;

final readonly class Username extends StringValueObject
{
    protected function validate(): void
    {
        if (empty(trim($this->value))) {
            throw new InvalidArgumentException('Username cannot be empty');
        }

        if (strlen($this->value) < 3) {
            throw new InvalidArgumentException('Username must be at least 3 characters long');
        }

        if (strlen($this->value) > 30) {
            throw new InvalidArgumentException('Username cannot exceed 30 characters');
        }

        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $this->value)) {
            throw new InvalidArgumentException('Username can only contain letters, numbers, underscore, hyphen and dot');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function normalize(): string
    {
        return strtolower($this->value);
    }
}
