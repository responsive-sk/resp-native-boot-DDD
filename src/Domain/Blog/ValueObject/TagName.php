<?php
// src/Domain/Blog/ValueObject/TagName.php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

use Blog\Domain\Shared\ValueObject\StringValueObject;
use InvalidArgumentException;

final readonly class TagName extends StringValueObject
{
    protected function validate(): void
    {
        if (empty(trim($this->value))) {
            throw new InvalidArgumentException('Tag name cannot be empty');
        }

        if (strlen($this->value) > 30) {
            throw new InvalidArgumentException('Tag name cannot exceed 30 characters');
        }

        // Tags should be lowercase and URL-friendly (approximately) based on prompt
        $normalized = strtolower($this->value);
        if (!preg_match('/^[a-z0-9\s\-]+$/', $normalized)) {
            throw new InvalidArgumentException('Tag name can only contain letters, numbers, spaces and hyphens');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function normalize(): string
    {
        return strtolower(trim($this->value));
    }

    public function toSlug(): string
    {
        $slug = strtolower($this->value);
        $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);
        $slug = preg_replace('/\s+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);

        return trim($slug, '-');
    }
}
