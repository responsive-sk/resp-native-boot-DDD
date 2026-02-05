<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\Repository;

use Blog\Domain\Blog\Entity\Tag;
use Blog\Domain\Blog\ValueObject\TagId;
use Blog\Domain\Blog\ValueObject\TagSlug;

interface TagRepository
{
    public function add(Tag $tag): void;

    public function remove(TagId $id): void;

    public function getById(TagId $id): ?Tag;

    public function getBySlug(TagSlug $slug): ?Tag;

    /**
     * @return Tag[]
     */
    public function getAll(): array;

    /**
     * Check if tag name already exists (excluding current tag)
     */
    public function nameExists(string $name, ?TagId $excludeId = null): bool;

    /**
     * Get tags by article ID
     * 
     * @return Tag[]
     */
    public function getByArticleId(int $articleId): array;

    /**
     * Add tag to article
     */
    public function addToArticle(int $articleId, TagId $tagId): void;

    /**
     * Remove tag from article
     */
    public function removeFromArticle(int $articleId, TagId $tagId): void;

    /**
     * Get or create tag by name
     */
    public function getOrCreateByName(string $name): Tag;
}
