<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Core\BaseUseCase;
use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;

final class GetAllArticles extends BaseUseCase
{
    public function __construct(
        private ArticleRepository $articles
    ) {
    }

    public function execute(array $input = []): array
    {
        $articles = $this->articles->getAll();
        
        $articlesData = array_map(function (Article $article) {
            return [
                'id' => $article->id()?->toInt(),
                'title' => $article->title()->toString(),
                'slug' => $article->slug()?->toString(),
                'content' => $article->content()->toString(),
                'status' => $article->status()->toString(),
                'author_id' => $article->authorId()->toString(),
                'created_at' => $article->createdAt()->format('Y-m-d H:i:s'),
                'updated_at' => $article->updatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $articles);

        return $this->success([
            'articles' => $articlesData,
            'count' => count($articlesData)
        ]);
    }
    
    protected function validate(array $input): void
    {
        // No validation required for this use case
    }
}
