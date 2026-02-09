<?php
// src/Domain/User/ValueObject/Email.php
declare(strict_types=1);

namespace Blog\Domain\User\ValueObject;

use Blog\Domain\Shared\ValueObject\StringValueObject;
use InvalidArgumentException;

final readonly class Email extends StringValueObject
{
    protected function validate(): void
    {
        if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function getDomain(): string
    {
        return explode('@', $this->value)[1] ?? '';
    }

    public function getLocalPart(): string
    {
        return explode('@', $this->value)[0] ?? '';
    }
}
