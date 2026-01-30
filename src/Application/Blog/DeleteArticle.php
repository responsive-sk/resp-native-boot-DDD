<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;

final class DeleteArticle
{
    public function __construct(
        private ArticleRepository $articles
    ) {
    }

    public function __invoke(ArticleId $articleId): void
    {
        // 1. Skontrolovať, či článok existuje
        $article = $this->articles->getById($articleId);

        if ($article === null) {
            throw new \DomainException('Article not found');
        }

        // 2. Odstrániť článok
        $this->articles->remove($articleId);
    }
}
