<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\Repository;

use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\CategoryId;
use Blog\Domain\Blog\ValueObject\Slug;

interface ArticleRepository
{
    public function add(Article $article): void;

    public function update(Article $article): void;

    public function remove(ArticleId $id): void;

    public function getById(ArticleId $id): ?Article;

    public function getBySlug(Slug $slug): ?Article;

    /**
     * @return Article[]
    */

    public function getAll(): array;

    /**
     * @return Article[]
     */
    public function search(string $query): array;

    /**
     * @return Article[]
     */
    public function getByCategory(CategoryId $categoryId): array;

    /**
     * @return Article[]
     */
    public function getRecentArticles(int $limit = 10): array;

    public function count(array $filters = []): int;

}
