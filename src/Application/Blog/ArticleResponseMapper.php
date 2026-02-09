<?php
// src/Application/Blog/ArticleResponseMapper.php
declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Domain\Blog\Entity\Article;

class ArticleResponseMapper
{
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
