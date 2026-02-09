<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Core\BaseUseCase;
use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;

final readonly class GetAllArticles extends BaseUseCase
{
    public function __construct(
        private ArticleRepository $articles
    ) {}

    protected function handle(array $input): array
    {
        $articles = $this->articles->getAll();
        
        $articlesData = array_map(fn(Article $article) => $article->toArray(), $articles ?? []);
        
        return $this->success([
            'articles' => $articlesData,
            'count' => count($articlesData),
            'meta' => [
                'total' => count($articlesData),
                'page' => $input['page'] ?? 1,
                'per_page' => $input['per_page'] ?? count($articlesData),
            ]
        ]);
    }

    protected function validate(array $input): void
    {
        // Optional pagination validation
        if (isset($input['page']) && (!is_int($input['page']) || $input['page'] < 1)) {
            throw new \InvalidArgumentException('Page must be a positive integer');
        }
        
        if (isset($input['per_page']) && (!is_int($input['per_page']) || $input['per_page'] < 1 || $input['per_page'] > 100)) {
            throw new \InvalidArgumentException('Per page must be between 1 and 100');
        }
    }
}