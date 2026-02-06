<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Persistence\Doctrine;

use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\CategorySlug;
use Blog\Domain\Blog\ValueObject\CategoryId;
use Blog\Domain\Blog\ValueObject\Slug;
use Doctrine\DBAL\Connection;

final readonly class DoctrineArticleRepository implements ArticleRepository
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function add(Article $article): void
    {
        $id = $article->id();

        if ($id === null) {
            // INSERT new article
            $this->connection->insert('articles', [
                'title' => $article->title()->toString(),
                'slug' => $article->slug()?->toString(),
                'content' => $article->content()->toString(),
                'author_id' => $article->authorId()->toString(),
                'status' => $article->status()->toString(),
                'created_at' => $article->createdAt()->format('Y-m-d H:i:s'),
                'updated_at' => $article->updatedAt()->format('Y-m-d H:i:s'),
                'category_id' => $article->category()?->id()?->toString(),
            ]);

            // Set the generated ID
            $generatedId = (int) $this->connection->lastInsertId();
            $article->setId(ArticleId::fromInt($generatedId));
        } else {
            // UPDATE existing article
            $this->connection->update('articles', [
                'title' => $article->title()->toString(),
                'slug' => $article->slug()?->toString(),
                'content' => $article->content()->toString(),
                'author_id' => $article->authorId()->toString(),
                'status' => $article->status()->toString(),
                'updated_at' => $article->updatedAt()->format('Y-m-d H:i:s'),
                'category_id' => $article->category()?->id()?->toString(),
            ], ['id' => $id->toInt()]);
        }
    }

    public function update(Article $article): void
    {
        $this->add($article);
    }

    public function remove(ArticleId $id): void
    {
        $this->connection->delete('articles', ['id' => $id->toInt()]);
    }

    public function getById(ArticleId $id): ?Article
    {
        $row = $this->connection->fetchAssociative(
            'SELECT * FROM articles WHERE id = ?',
            [$id->toInt()]
        );

        if (!$row) {
            return null;
        }

        return $this->hydrate($row);
    }

    public function getBySlug(Slug $slug): ?Article
    {
        $row = $this->connection->fetchAssociative(
            'SELECT * FROM articles WHERE slug = ?',
            [$slug->toString()]
        );

        if (!$row) {
            return null;
        }

        return $this->hydrate($row);
    }

    public function getAll(): array
    {
        // Debug DB path
        $params = $this->connection->getParams();

        // Fetch ALL articles first to debug the filtering issue
        // Temporarily fetching all because the WHERE clause was failing mysteriously
        $rows = $this->connection->fetchAllAssociative(
            "SELECT * FROM articles ORDER BY created_at DESC"
        );

        // Filter in PHP
        $rows = array_filter($rows, function ($row) {
            return trim($row['status'] ?? '') === 'published';
        });

        if (empty($rows)) {
            return [];
        }

        $articleIds = array_column($rows, 'id');
        $categoryIds = array_filter(array_unique(array_column($rows, 'category_id')));

        // 2. Load all categories at once
        $categories = [];
        if (!empty($categoryIds)) {
            $categoriesData = $this->connection->fetchAllAssociative(
                'SELECT * FROM categories WHERE id IN (?)',
                [$categoryIds],
                [\Doctrine\DBAL\ArrayParameterType::STRING]
            );
            foreach ($categoriesData as $cat) {
                $categories[$cat['id']] = $cat;
            }
        }

        // 3. Load all tags for these articles at once
        $tagsData = [];
        if (!empty($articleIds)) {
            $tagsData = $this->connection->fetchAllAssociative(
                'SELECT t.*, at.article_id 
                 FROM tags t
                 INNER JOIN article_tags at ON t.id = at.tag_id
                 WHERE at.article_id IN (?)
                 ORDER BY t.name ASC',
                [$articleIds],
                [\Doctrine\DBAL\ArrayParameterType::INTEGER]
            );
        }

        $tagsByArticle = [];
        foreach ($tagsData as $tag) {
            $tagsByArticle[$tag['article_id']][] = $tag;
        }

        // 4. Hydrate with preloaded data
        return array_map(function ($row) use ($categories, $tagsByArticle) {
            return $this->hydrateWithPreloadedData(
                $row,
                $categories[$row['category_id'] ?? ''] ?? null,
                $tagsByArticle[$row['id']] ?? []
            );
        }, $rows);
    }

    private function hydrateWithPreloadedData(array $row, ?array $categoryData, array $tagsData): Article
    {
        $category = null;
        if ($categoryData) {
            $category = \Blog\Domain\Blog\Entity\Category::reconstitute(
                \Blog\Domain\Blog\ValueObject\CategoryId::fromString($categoryData['id']),
                \Blog\Domain\Blog\ValueObject\CategoryName::fromString($categoryData['name']),
                \Blog\Domain\Blog\ValueObject\CategorySlug::fromString($categoryData['slug']),
                $categoryData['description'],
                new \DateTimeImmutable($categoryData['created_at']),
                new \DateTimeImmutable($categoryData['updated_at'])
            );
        }

        $tags = array_map([$this, 'hydrateTag'], $tagsData);

        return Article::reconstitute(
            ArticleId::fromInt((int) $row['id']),
            \Blog\Domain\Blog\ValueObject\Title::fromString($row['title']),
            \Blog\Domain\Blog\ValueObject\Content::fromString($row['content']),
            \Blog\Domain\Blog\ValueObject\AuthorId::fromString($row['author_id']),
            \Blog\Domain\Blog\ValueObject\ArticleStatus::fromString($row['status']),
            new \DateTimeImmutable($row['created_at']),
            new \DateTimeImmutable($row['updated_at']),
            $row['slug'] ? \Blog\Domain\Blog\ValueObject\Slug::fromString($row['slug']) : null,
            $category,
            $tags
        );
    }

    public function search(string $query): array
    {
        if (empty(trim($query))) {
            return [];
        }

        // Split query into terms for FTS5 (no wildcards needed for prefix matching)
        $terms = array_filter(explode(' ', trim($query)));
        $ftsQuery = implode(' ', $terms);

        // Use FTS5 virtual table for full-text search
        $sql = "
            SELECT a.*
            FROM articles a
            INNER JOIN articles_fts fts ON a.id = fts.article_id
            WHERE articles_fts MATCH ?
            AND a.status = 'published'
            ORDER BY rank
            LIMIT 50
        ";

        $rows = $this->connection->fetchAllAssociative($sql, [$ftsQuery]);

        return array_map([$this, 'hydrate'], $rows);
    }

    public function getByCategory(CategoryId $categoryId): array
    {
        $rows = $this->connection->fetchAllAssociative(
            "SELECT * FROM articles WHERE category_id = ? AND status = 'published' ORDER BY created_at DESC",
            [$categoryId->toString()]
        );

        return array_map([$this, 'hydrate'], $rows);
    }

    public function getCategories(): array
    {
        return [];
    }

    private function loadTagsForArticle(int $articleId): array
    {
        try {
            $qb = $this->connection->createQueryBuilder();
            $results = $qb
                ->select('t.*')
                ->from('tags', 't')
                ->innerJoin('t', 'article_tags', 'at', 't.id = at.tag_id')
                ->where('at.article_id = :article_id')
                ->setParameter('article_id', $articleId)
                ->orderBy('t.name', 'ASC')
                ->fetchAllAssociative();

            return array_map([$this, 'hydrateTag'], $results);
        } catch (\Exception $e) {
            // Ak tabuľka neexistuje alebo iná chyba, vráť prázdne pole
            return [];
        }
    }

    private function hydrateTag(array $data): \Blog\Domain\Blog\Entity\Tag
    {
        return \Blog\Domain\Blog\Entity\Tag::reconstitute(
            \Blog\Domain\Blog\ValueObject\TagId::fromString($data['id']),
            \Blog\Domain\Blog\ValueObject\TagName::fromString($data['name']),
            \Blog\Domain\Blog\ValueObject\TagSlug::fromString($data['slug']),
            new \DateTimeImmutable($data['created_at'])
        );
    }

    private function hydrate(array $row): Article
    {
        $category = null;
        if (!empty($row['category_id'])) {
            // Load category - for simplicity, we'll create a minimal category
            // In production, you might want to inject CategoryRepository
            $categoryData = $this->connection->fetchAssociative(
                'SELECT * FROM categories WHERE id = ?',
                [$row['category_id']]
            );

            return $this->hydrateWithPreloadedData($row, $categoryData ?: null, $this->loadTagsForArticle((int) $row['id']));
        }

        return $this->hydrateWithPreloadedData($row, null, $this->loadTagsForArticle((int) $row['id']));
    }
}
