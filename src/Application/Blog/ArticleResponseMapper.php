<?php
// src/Application/Blog/ArticleResponseMapper.php
declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Domain\Blog\Entity\Article;

class ArticleResponseMapper
{
    /**
     * Full article representation (replaces Article::toArray())
     */
    public function toArray(Article $article): array
    {
        return [
            'id' => $article->getId()->toString(),
            'title' => $article->getTitle()->toString(),
            'slug' => $article->getSlug()->toString(),
            'content' => $article->getContent()->toString(),
            'excerpt' => $article->getExcerpt(),
            'status' => $article->getStatus()->value,
            'author_id' => $article->getAuthorId()->toString(),
            'category_id' => $article->getCategory()?->getId()->toString(),
            'category_name' => $article->getCategory()?->getName()->toString(),
            'featured_image' => $article->getFeaturedImage(),
            'meta_description' => $article->getMetaDescription(),
            'view_count' => $article->getViewCount(),
            'tag_ids' => $article->getTagIds(),
            'created_at' => $article->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $article->getUpdatedAt()->format('Y-m-d H:i:s'),
            'published_at' => $article->getPublishedAt()?->format('Y-m-d H:i:s'),
            'scheduled_at' => $article->getScheduledAt()?->format('Y-m-d H:i:s'),
            'can_edit' => $article->canEdit(),
        ];
    }

    /**
     * Summary article representation (replaces Article::toSummaryArray())
     */
    public function toSummaryArray(Article $article): array
    {
        return [
            'id' => $article->getId()->getValue(),
            'title' => $article->getTitle()->getValue(),
            'slug' => $article->getSlug()->getValue(),
            'excerpt' => $article->getExcerpt(),
            'status' => $article->getStatus()->value,
            'author_id' => $article->getAuthorId()->toString(),
            'featured_image' => $article->getFeaturedImage(),
            'view_count' => $article->getViewCount(),
            'created_at' => $article->getCreatedAt()->format('Y-m-d H:i:s'),
            'published_at' => $article->getPublishedAt()?->format('Y-m-d H:i:s'),
        ];
    }

    public function map(Article $article): array
    {
        return [
            'id' => (string) $article->id(),
            'title' => (string) $article->title(),
            'slug' => $article->slug()?->toString(),
            'excerpt' => $article->content()->getExcerpt(),
            'content' => $article->content()->getHtml(),
            'status' => $this->mapStatus($article->status()->value),
            // Null-safe operator usage as requested
            'category' => [
                'name' => $article->category()?->getName()->toString() ?? 'Uncategorized',
                'slug' => $article->category()?->getSlug()->toString(),
            ],
            'tags' => array_map(fn($tag) => $tag->getName(), $article->tags()),
            'author_id' => (string) $article->authorId(),
            'created_at' => $article->createdAt()->format(\DateTimeInterface::ATOM),
            'updated_at' => $article->updatedAt()->format(\DateTimeInterface::ATOM),
        ];
    }

    public function mapStatus(string $status): array
    {
        return match ($status) {
            'draft' => ['label' => 'Draft', 'color' => 'gray', 'icon' => '✏️'],
            'published' => ['label' => 'Published', 'color' => 'green', 'icon' => '✅'],
            'archived' => ['label' => 'Archived', 'color' => 'blue', 'icon' => '📁'],
            'scheduled' => ['label' => 'Scheduled', 'color' => 'orange', 'icon' => '⏰'],
            default => ['label' => 'Unknown', 'color' => 'red', 'icon' => '❓'],
        };
    }
}
