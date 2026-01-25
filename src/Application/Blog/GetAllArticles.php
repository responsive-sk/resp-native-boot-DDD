<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\Entity\Article;

final class GetAllArticles
{
    public function __construct(
        private ArticleRepository $articles
    ) {}

    /**
     * @return Article[]
     */
    public function __invoke(): array
    {
        return $this->articles->getAll();
    }
}
