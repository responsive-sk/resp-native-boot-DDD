<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Core\BaseUseCase;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;

final readonly class GetArticleById extends BaseUseCase
{
    public function __construct(
        private ArticleRepository $articles,
        private ArticleResponseMapper $mapper
    ) {
    }

    protected function handle(array $input): array
    {
        if (empty($input['article_id'])) {
            throw new \InvalidArgumentException('Article ID is required');
        }

        $id = (int) $input['article_id'];
        $article = $this->articles->getById(ArticleId::fromInt($id));

        if ($article === null) {
            throw new \Exception('Article not found');
        }

        return $this->success([
            'article' => $this->mapper->toArray($article),
            'author_id' => $article->getAuthorId()->toString()
        ]);
    }

    protected function validate(array $input): void
    {
        if (empty($input['article_id'])) {
            throw new \InvalidArgumentException('Article ID is required');
        }

        if (!is_numeric($input['article_id']) || (int) $input['article_id'] <= 0) {
            throw new \InvalidArgumentException('Article ID must be a positive integer');
        }
    }
}
