<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\Entity;

use Blog\Domain\Blog\ValueObject\CategoryId;
use Blog\Domain\Blog\ValueObject\CategoryName;
use Blog\Domain\Blog\ValueObject\CategorySlug;
use DateTimeImmutable;

final class Category
{
    private function __construct(
        private ?CategoryId         $id,
        private CategoryName        $name,
        private CategorySlug        $slug,
        private ?string            $description,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {
    }

    public static function create(
        CategoryName $name,
        ?string $description = null
    ): self {
        $now = new DateTimeImmutable();

        return new self(
            id: null,
            name: $name,
            slug: CategorySlug::fromName($name),
            description: $description,
            createdAt: $now,
            updatedAt: $now
        );
    }

    public static function reconstitute(
        CategoryId $id,
        CategoryName $name,
        CategorySlug $slug,
        ?string $description,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ): self {
        return new self($id, $name, $slug, $description, $createdAt, $updatedAt);
    }

    public function update(CategoryName $name, ?string $description = null): void
    {
        $this->name = $name;
        $this->slug = CategorySlug::fromName($name);
        $this->description = $description;
        $this->updatedAt = new DateTimeImmutable();
    }

    // Getters
    public function id(): ?CategoryId
    {
        return $this->id;
    }

    public function name(): CategoryName
    {
        return $this->name;
    }

    public function slug(): CategorySlug
    {
        return $this->slug;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    // For persistence layer to set ID after insert
    public function setId(CategoryId $id): void
    {
        if ($this->id !== null) {
            throw new \LogicException('Category ID už bolo nastavené');
        }
        $this->id = $id;
    }

    // Compatibility getters used by templates
    public function getName(): CategoryName
    {
        return $this->name();
    }

    public function getSlug(): CategorySlug
    {
        return $this->slug();
    }

    public function getDescription(): ?string
    {
        return $this->description();
    }
}
