<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Core\BaseUseCase;
use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\Content;
use Blog\Domain\Blog\ValueObject\Slug;
use Blog\Domain\Blog\ValueObject\Title;
use Blog\Domain\User\ValueObject\UserId;

final class CreateArticle extends BaseUseCase
{
    public function __construct(
        private ArticleRepository $articles
    ) {
    }

    public function execute(array $input): array
    {
        $this->validate($input);

        $title = $input['title'];
        $content = $input['content'];
        $authorId = $input['author_id'];

        // 1. Vytvor základný Slug z titulu
        $baseSlug = $title; // Slug will be created in Article::create
        $slug = $baseSlug;
        $suffix = 1;

        // 2. Najdi unikátny slug (efektívnejšie ako v loope)
        $uniqueSlug = $this->findUniqueSlug($baseSlug, $suffix);

        // 3. Vytvor článok s unikátnym slugom
        $article = Article::create(
            Title::fromString($title),
            Content::fromString($content),
            UserId::fromString($authorId)
        );

        // Nastav unikátny slug
        $article->setSlug(new Slug($uniqueSlug));

        // 4. Uložiť
        $this->articles->add($article);

        $id = $article->id();

        if ($id === null) {
            throw new \RuntimeException('Article ID could not be generated.');
        }

        return $this->success([
            'article_id' => $id->toInt(),
            'article' => $article
        ]);
    }

    /**
     * Efektívne nájde unikátny slug bez zbytočných databázových dotazov
     */
    private function findUniqueSlug(string $baseSlug, int &$suffix): string
    {
        $maxAttempts = 100; // Ochrana proti infinite loop
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            $candidateSlug = $attempt === 0 ? $baseSlug : $baseSlug . '-' . $suffix;
            
            if ($this->articles->getBySlug(new Slug($candidateSlug)) === null) {
                return $candidateSlug;
            }
            
            $suffix++;
            $attempt++;
        }

        throw new \RuntimeException('Unable to generate unique slug after ' . $maxAttempts . ' attempts');
    }

    protected function validate(array $input): void
    {
        if (empty($input['title'])) {
            throw new \InvalidArgumentException('Title is required');
        }

        if (empty($input['content'])) {
            throw new \InvalidArgumentException('Content is required');
        }

        if (empty($input['author_id'])) {
            throw new \InvalidArgumentException('Author ID is required');
        }

        if (strlen($input['title']) > 255) {
            throw new \InvalidArgumentException('Title must not exceed 255 characters');
        }

        if (strlen($input['content']) < 10) {
            throw new \InvalidArgumentException('Content must be at least 10 characters long');
        }
    }
}
