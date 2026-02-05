<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

final readonly class TagSlug
{
    private string $value;

    private function __construct(string $value)
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('Slug tagu nemôže byť prázdny');
        }

        if (!preg_match('/^[a-z0-9-]+$/', $value)) {
            throw new \InvalidArgumentException('Slug môže obsahovať len malé písmená, čísla a pomlčky');
        }

        $this->value = trim($value);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function fromName(TagName $name): self
    {
        $slug = strtolower($name->toString());
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        $slug = preg_replace('/-+/', '-', $slug);

        return new self($slug);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(TagSlug $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
