<?php
// src/Domain/Blog/ValueObject/ArticleReconstitutionData.php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

use Blog\Domain\Blog\Entity\Category;
use Blog\Domain\Shared\Markdown\MarkdownContent;
use DateTimeImmutable;

/**
 * Data Transfer Object for Article reconstitution.
 * Encapsulates all data needed to recreate an Article from persistence.
 */
final readonly class ArticleReconstitutionData
{
    /**
     * @param array<int> $tagIds
     */
    public function __construct(
        public ArticleId $id,
        public Title $title,
        public MarkdownContent $content,
        public AuthorId $authorId,
        public ArticleStatus $status,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
        public ?Slug $slug = null,
        public ?Category $category = null,
        public ?string $excerpt = null,
        public ?string $featuredImage = null,
        public ?string $metaDescription = null,
        public int $viewCount = 0,
        public ?DateTimeImmutable $publishedAt = null,
        public ?DateTimeImmutable $scheduledAt = null,
        public array $tagIds = []
    ) {}

    /**
     * Create from array data (e.g., from database row).
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: ArticleId::fromString((string) $data['id']),
            title: Title::fromString($data['title']),
            content: new MarkdownContent($data['content']),
            authorId: AuthorId::fromString($data['author_id']),
            status: ArticleStatus::fromString($data['status']),
            createdAt: new DateTimeImmutable($data['created_at']),
            updatedAt: new DateTimeImmutable($data['updated_at']),
            slug: isset($data['slug']) ? Slug::fromString($data['slug']) : null,
            category: $data['category'] ?? null,
            excerpt: $data['excerpt'] ?? null,
            featuredImage: $data['featured_image'] ?? null,
            metaDescription: $data['meta_description'] ?? null,
            viewCount: (int) ($data['view_count'] ?? 0),
            publishedAt: isset($data['published_at']) ? new DateTimeImmutable($data['published_at']) : null,
            scheduledAt: isset($data['scheduled_at']) ? new DateTimeImmutable($data['scheduled_at']) : null,
            tagIds: $data['tag_ids'] ?? []
        );
    }
}
