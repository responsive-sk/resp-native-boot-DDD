<?php
// src/Domain/Blog/ValueObject/Content.php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

use Blog\Domain\Shared\ValueObject\StringValueObject;
use InvalidArgumentException;

final readonly class Content extends StringValueObject
{
    protected function validate(): void
    {
        if (empty(trim($this->value))) {
            throw new InvalidArgumentException('Content cannot be empty');
        }

        // PHP limit for string is typically large, but logic may restrict it.
        // The user request specified 10000 characters.
        if (strlen($this->value) > 10000) {
            throw new InvalidArgumentException('Content cannot exceed 10,000 characters');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function getExcerpt(int $length = 150): string
    {
        $text = strip_tags($this->value);
        $text = trim($text);

        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length) . '...';
    }

    public function getWordCount(): int
    {
        $text = strip_tags($this->value);
        $text = trim($text);

        if (empty($text)) {
            return 0;
        }

        return count(preg_split('/\s+/', $text));
    }

    public function contains(string $search): bool
    {
        return stripos($this->value, $search) !== false;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
