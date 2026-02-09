<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Response;

use Blog\Domain\Blog\Entity\Article;

final readonly class ArticleResponse
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public string $excerpt,
        public string $authorId,
        public string $status,
        public string $createdAt,
        public string $updatedAt
    ) {}

    public static function fromEntity(Article $article): self
    {
        return new self(
            id: $article->id()->toInt(),
            title: $article->title()->toString(),
            content: $article->content()->getRaw(),
            excerpt: $article->content()->excerpt(200),
            authorId: $article->authorId()->toString(),
            status: $article->status()->toString(),
            createdAt: $article->createdAt()->format('c'),
            updatedAt: $article->updatedAt()->format('c')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'authorId' => $this->authorId,
            'status' => $this->status,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
