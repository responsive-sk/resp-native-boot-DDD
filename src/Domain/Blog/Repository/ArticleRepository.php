<?php
// src/Domain/Blog/Repository/ArticleRepository.php - generická verze
declare(strict_types=1);

namespace Blog\Domain\Blog\Repository;

use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\Slug;
use Blog\Domain\Blog\ValueObject\CategoryId;

/**
 * @template T of Article
 */
interface ArticleRepository
{
    public function add(Article $article): void;
    
    public function update(Article $article): void;
    
    public function remove(ArticleId $id): void;
    
    /** @return T|null */
    public function getById(ArticleId $id): ?Article;
    
    /** @return T|null */
    public function getBySlug(Slug $slug): ?Article;
    
    /**
     * Get all articles without filtering.
     * Business logic filtering should be done in use cases.
     *
     * @return array<T>
     */
    public function getAll(): array;

    /**
     * Find only published articles.
     * Use case: Public article listings.
     *
     * @return array<T>
     */
    public function findPublished(): array;

    /**
     * Find articles by status.
     * Use case: Admin dashboards, filtered views.
     *
     * @return array<T>
     */
    public function findByStatus(string $status): array;

    /** @return array<T> */
    public function search(string $query): array;

    /** @return array<T> */
    public function getByCategory(CategoryId $categoryId): array;

    /** @return array<T> */
    public function getRecentArticles(int $limit = 10): array;

    public function count(array $filters = []): int;
}
