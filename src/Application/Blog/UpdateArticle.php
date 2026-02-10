<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Core\BaseUseCase;
use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Shared\Markdown\MarkdownContent;
use Blog\Domain\Blog\ValueObject\Slug;
use Blog\Domain\Blog\ValueObject\Title;
use DomainException;

final class UpdateArticle extends BaseUseCase
{
    public function __construct(
        private ArticleRepository $articles
    ) {}

    protected function handle(array $input): array
    {
        $articleId = ArticleId::fromInt((int) $input['article_id']);
        $title = Title::fromString($input['title']);
        $content = new MarkdownContent($input['content']);
        $slug = isset($input['slug']) ? new Slug($input['slug']) : null;

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

            if ($existing !== null) {
                $existingId = $existing->id();

                // If existing article has no ID (should impossible for persisted entity), we can't compare.
                // Assuming persisted entities have IDs.
                if ($existingId !== null && !$existingId->equals($articleId)) {
                    throw new DomainException('Slug already exists');
                }
            }
        }

        // 4. Uložiť zmeny
        $this->articles->update($article);

        return $this->success([
            'article' => $article,
            'article_id' => $articleId->toInt(),
        ]);
    }

    protected function validate(array $input): void
    {
        if (empty($input['article_id'])) {
            throw new \InvalidArgumentException('Article ID is required');
        }

        if (empty($input['title'])) {
            throw new \InvalidArgumentException('Title is required');
        }

        if (empty($input['content'])) {
            throw new \InvalidArgumentException('Content is required');
        }

        if (!is_numeric($input['article_id']) || (int) $input['article_id'] <= 0) {
            throw new \InvalidArgumentException('Invalid article ID');
        }

        if (strlen($input['title']) > 255) {
            throw new \InvalidArgumentException('Title must not exceed 255 characters');
        }

        if (strlen($input['content']) < 10) {
            throw new \InvalidArgumentException('Content must be at least 10 characters long');
        }

        if (isset($input['slug']) && strlen($input['slug']) > 255) {
            throw new \InvalidArgumentException('Slug must not exceed 255 characters');
        }
    }
}
