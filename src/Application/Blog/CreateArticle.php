<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\Content;
use Blog\Domain\Blog\ValueObject\Slug;
use Blog\Domain\Blog\ValueObject\Title;
use Blog\Domain\User\ValueObject\UserId;

final readonly class CreateArticle
{
    public function __construct(
        private ArticleRepository $articles
    ) {
    }

    public function __invoke(
        string $title,
        string $content,
        string $authorId
    ): \Blog\Domain\Blog\ValueObject\ArticleId {
        // 1. Vytvor Slug priamo z titulu - Slug trieda sa postará o normalizáciu
        $slug = new Slug($title);

        // 2. Vytvor článok
        $article = Article::create(
            Title::fromString($title),
            Content::fromString($content),
            UserId::fromString($authorId)
        );

        // Nastav slug
        $article->setSlug($slug);

        // 3. Zabezpečiť unikátnosť
        $i = 1;
        $originalSlug = $slug->toString();
        while ($this->articles->getBySlug($slug) !== null) {
            $slug = new Slug($originalSlug . '-' . $i);
            $article->setSlug($slug);
            $i++;
        }

        // 4. Uložiť
        $this->articles->add($article);

        $id = $article->id();
        if ($id === null) {
            throw new \RuntimeException('Article ID could not be generated.');
        }

        return $id;
    }
}
