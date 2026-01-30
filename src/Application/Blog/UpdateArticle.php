<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\Content;
use Blog\Domain\Blog\ValueObject\Slug;
use Blog\Domain\Blog\ValueObject\Title;
use DomainException;

final readonly class UpdateArticle
{
    public function __construct(
        private ArticleRepository $articles
    ) {
    }

    public function __invoke(
        ArticleId $articleId,
        Title $title,
        Content $content,
        ?Slug $slug = null
    ): Article {
        // 1. Nájsť článok
        $article = $this->articles->getById($articleId);

        if ($article === null) {
            throw new DomainException('Article not found');
        }

        // 2. Aktualizovať článok
        $article->update($title, $content);

        // 3. Aktualizovať slug (ak je zadaný)
        if ($slug !== null) {
            $article->setSlug($slug);

            // Skontrolovať unikátnosť slug (okrem aktuálneho článku)
            $existing = $this->articles->getBySlug($slug);
            if ($existing !== null && !$existing->id()->equals($articleId)) {
                throw new DomainException('Slug already exists');
            }
        }

        // 4. Uložiť zmeny
        $this->articles->update($article);

        return $article;
    }
}
