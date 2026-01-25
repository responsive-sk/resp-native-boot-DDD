<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

use InvalidArgumentException;

final readonly class ArticleStatus
{
    private const string DRAFT = 'draft';
    private const string PUBLISHED = 'published';
    private const string ARCHIVED = 'archived';

    private const array VALID_STATUSES = [
        self::DRAFT,
        self::PUBLISHED,
        self::ARCHIVED,
    ];

    private function __construct(private string $value)
    {
        if (!in_array($value, self::VALID_STATUSES, true)) {
            throw new InvalidArgumentException(
                sprintf('Neplatný status: %s', $value)
            );
        }
    }

    public static function draft(): self
    {
        return new self(self::DRAFT);
    }

    public static function published(): self
    {
        return new self(self::PUBLISHED);
    }

    public static function archived(): self
    {
        return new self(self::ARCHIVED);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function isDraft(): bool
    {
        return $this->value === self::DRAFT;
    }

    public function isPublished(): bool
    {
        return $this->value === self::PUBLISHED;
    }

    public function isArchived(): bool
    {
        return $this->value === self::ARCHIVED;
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
