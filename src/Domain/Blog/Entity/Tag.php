<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\Entity;

use Blog\Domain\Blog\ValueObject\TagId;
use Blog\Domain\Blog\ValueObject\TagName;
use Blog\Domain\Blog\ValueObject\TagSlug;
use DateTimeImmutable;

final class Tag
{
    private function __construct(
        private ?TagId $id,
        private TagName $name,
        private TagSlug $slug,
        private readonly DateTimeImmutable $createdAt
    ) {
    }

    public static function create(TagName $name): self
    {
        $now = new DateTimeImmutable();

        return new self(
            id: null,
            name: $name,
            slug: TagSlug::fromName($name),
            createdAt: $now
        );
    }

    public static function reconstitute(
        TagId $id,
        TagName $name,
        TagSlug $slug,
        DateTimeImmutable $createdAt
    ): self {
        return new self($id, $name, $slug, $createdAt);
    }

    // Getters
    public function id(): ?TagId
    {
        return $this->id;
    }

    public function name(): TagName
    {
        return $this->name;
    }

    public function slug(): TagSlug
    {
        return $this->slug;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    // For persistence layer to set ID after insert
    public function setId(TagId $id): void
    {
        if ($this->id !== null) {
            throw new \LogicException('Tag ID už bolo nastavené');
        }
        $this->id = $id;
    }

    // Compatibility getters used by templates
    public function getName(): TagName
    {
        return $this->name();
    }

    public function getSlug(): TagSlug
    {
        return $this->slug();
    }
}
