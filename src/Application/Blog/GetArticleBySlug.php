<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Core\BaseUseCase;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\Slug;

final class GetArticleBySlug extends BaseUseCase
{
    public function __construct(
        private ArticleRepository $articles
    ) {
    }

    public function execute(array $input): array
    {
        $this->validate($input);

        $slug = new Slug($input['slug']);
        $article = $this->articles->getBySlug($slug);

        if ($article === null) {
            throw new \DomainException('Article not found');
        }

        return $this->success([
            'article' => [
                'id' => $article->id()?->toInt(),
                'title' => $article->title()->toString(),
                'slug' => $article->slug()?->toString(),
                'content' => $article->content()->getRaw(),
                'status' => $article->status()->toString(),
                'author_id' => $article->authorId()->toString(),
                'created_at' => $article->createdAt()->format('Y-m-d H:i:s'),
                'updated_at' => $article->updatedAt()->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    protected function validate(array $input): void
    {
        if (empty($input['slug'])) {
            throw new \InvalidArgumentException('Slug is required');
        }

        if (strlen($input['slug']) > 255) {
            throw new \InvalidArgumentException('Slug must not exceed 255 characters');
        }

        if (!preg_match('/^[a-z0-9\-]+$/', $input['slug'])) {
            throw new \InvalidArgumentException('Slug can only contain lowercase letters, numbers, and hyphens');
        }
    }
}
