<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Core\BaseUseCase;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;

final class DeleteArticle extends BaseUseCase
{
    public function __construct(
        private ArticleRepository $articles
    ) {}

    protected function handle(array $input): array
    {
        $articleId = ArticleId::fromInt((int) $input['article_id']);

        // 1. Skontrolovať, či článok existuje
        $article = $this->articles->getById($articleId);

        if ($article === null) {
            throw new \DomainException('Article not found');
        }

        // 2. Odstrániť článok
        $this->articles->remove($articleId);

        return $this->success([
            'message' => 'Article deleted successfully',
            'article_id' => $articleId->toInt(),
        ]);
    }

    protected function validate(array $input): void
    {
        if (empty($input['article_id'])) {
            throw new \InvalidArgumentException('Article ID is required');
        }

        if (!is_numeric($input['article_id']) || (int) $input['article_id'] <= 0) {
            throw new \InvalidArgumentException('Invalid article ID');
        }
    }
}
