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

final readonly class CreateArticle extends BaseUseCase
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

        return $this->success([
            'article_id' => $id->toInt(),
            'article' => $article
        ]);
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
