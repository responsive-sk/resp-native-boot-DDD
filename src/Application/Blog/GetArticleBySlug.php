<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\Slug;

final readonly class GetArticleBySlug
{
    public function __construct(
        private ArticleRepository $articles
    ) {
    }

    public function __invoke(Slug $slug): ?Article
    {
        return $this->articles->getBySlug($slug);
    }
}
