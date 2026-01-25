<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Domain\Blog\Repository\ArticleRepository;

final class SearchArticles
{
    public function __construct(
        private ArticleRepository $articles
    ) {}

    public function __invoke(string $query): array
    {
        return $this->articles->search($query);
    }
}

