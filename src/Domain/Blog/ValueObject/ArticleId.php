<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

use InvalidArgumentException;

final readonly class ArticleId
{
    private string $id;

    private function __construct(string $id)
    {
        if (empty($id)) {
            throw new InvalidArgumentException('Article ID cannot be empty.');
        }
        $this->id = $id;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->id;
    }

    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
