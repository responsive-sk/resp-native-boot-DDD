<?php
// src/Domain/Blog/ValueObject/ArticleStatus.php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

enum ArticleStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case SCHEDULED = 'scheduled';

    public static function fromString(string $value): self
    {
        return self::tryFrom($value) ?? throw new InvalidArgumentException("Invalid article status: {$value}");
    }

    public function isPublished(): bool
    {
        return $this === self::PUBLISHED;
    }

    public function isDraft(): bool
    {
        return $this === self::DRAFT;
    }

    public function canEdit(): bool
    {
        return match ($this) {
            self::DRAFT, self::SCHEDULED => true,
            self::PUBLISHED, self::ARCHIVED => false,
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::PUBLISHED => 'green',
            self::ARCHIVED => 'blue',
            self::SCHEDULED => 'orange',
        };
    }
}
