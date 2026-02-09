<?php
// src/Domain/Blog/ValueObject/AuthorName.php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

use Blog\Domain\Shared\ValueObject\StringValueObject;
use InvalidArgumentException;

final readonly class AuthorName extends StringValueObject
{
    protected function validate(): void
    {
        if (empty(trim($this->value))) {
            throw new InvalidArgumentException('Author name cannot be empty');
        }

        if (strlen($this->value) > 100) {
            throw new InvalidArgumentException('Author name cannot exceed 100 characters');
        }
    }

    public function getFirstName(): string
    {
        $parts = explode(' ', $this->value);
        return $parts[0] ?? '';
    }

    public function getLastName(): string
    {
        $parts = explode(' ', $this->value);

        if (count($parts) < 2) {
            return '';
        }

        return $parts[count($parts) - 1];
    }

    public function getInitials(): string
    {
        $parts = explode(' ', $this->value);
        $initials = '';

        foreach ($parts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper(substr($part, 0, 1));
            }
        }

        return $initials;
    }
}
