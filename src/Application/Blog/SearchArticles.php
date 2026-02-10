<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Core\BaseUseCase;
use Blog\Domain\Blog\Repository\ArticleRepository;

final class SearchArticles extends BaseUseCase
{
    public function __construct(
        private ArticleRepository $articles
    ) {}

    protected function handle(array $input): array
    {
        $this->validate($input);

        $query = $input['query'];
        $articles = $this->articles->search($query);

        $articlesData = array_map(function ($article) {
            return [
                'id' => $article->getId()->toString(),
                'title' => $article->getTitle()->toString(),
                'slug' => $article->getSlug()->toString(),
                'content' => substr($article->getContent()->toString(), 0, 200) . '...',
                'status' => $article->getStatus()->value,
                'author_id' => $article->getAuthorId()->toString(),
                'created_at' => $article->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $articles);

        return $this->success([
            'articles' => $articlesData,
            'count' => count($articlesData),
            'query' => $query,
        ]);
    }

    protected function validate(array $input): void
    {
        if (empty($input['query'])) {
            throw new \InvalidArgumentException('Search query is required');
        }

        if (strlen($input['query']) < 2) {
            throw new \InvalidArgumentException('Search query must be at least 2 characters long');
        }

        if (strlen($input['query']) > 255) {
            throw new \InvalidArgumentException('Search query must not exceed 255 characters');
        }
    }
}
