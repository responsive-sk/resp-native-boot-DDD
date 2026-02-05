<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\Repository;

use Blog\Domain\Blog\Entity\Category;
use Blog\Domain\Blog\ValueObject\CategoryId;
use Blog\Domain\Blog\ValueObject\CategorySlug;

interface CategoryRepository
{
    public function add(Category $category): void;

    public function update(Category $category): void;

    public function remove(CategoryId $id): void;

    public function getById(CategoryId $id): ?Category;

    public function getBySlug(CategorySlug $slug): ?Category;

    /**
     * @return Category[]
     */
    public function getAll(): array;

    /**
     * @return Category[]
     */
    public function getActive(): array;

    /**
     * Check if category name already exists (excluding current category)
     */
    public function nameExists(string $name, ?CategoryId $excludeId = null): bool;
}
