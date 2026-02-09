<?php
// src/Domain/Blog/ValueObject/Slug.php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

use Blog\Domain\Shared\ValueObject\StringValueObject;
use InvalidArgumentException;

final readonly class Slug extends StringValueObject
{
    protected function validate(): void
    {
        if (!preg_match('/^[a-z0-9\-]+$/', $this->value)) {
            throw new InvalidArgumentException(
                'Slug can only contain lowercase letters, numbers and hyphens'
            );
        }

        if (strlen($this->value) > 200) {
            throw new InvalidArgumentException('Slug cannot exceed 200 characters');
        }
    }

    public static function fromString(string $text): self
    {
        $slug = strtolower($text);
        $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);
        $slug = preg_replace('/\s+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        return new self($slug);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
