<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Persistence\Doctrine;

use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\CategoryId;
use Blog\Domain\Blog\ValueObject\Slug;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

final readonly class DoctrineArticleRepository implements ArticleRepository
{
    public function __construct(
        private Connection $connection
    ) {}

    public function add(Article $article): void
    {
        $id = $article->id();

        if ($id === null) {
            // INSERT new article
            $this->connection->insert('articles', [
                'title' => $article->title()->toString(),
                'slug' => $article->slug()?->toString(),
                'content' => $article->content()->getRaw(),
                'author_id' => $article->authorId()->toString(),
                'status' => $article->status()->toString(),
                'created_at' => $article->createdAt()->format('Y-m-d H:i:s'),
                'updated_at' => $article->updatedAt()->format('Y-m-d H:i:s'),
                'category_id' => $article->category()?->id()?->toString(),
            ]);

            // Set the generated ID
            $generatedId = (string) $this->connection->lastInsertId();
            $article->setId(ArticleId::fromString($generatedId));
        } else {
            // UPDATE existing article
            $this->connection->update('articles', [
                'title' => $article->title()->toString(),
                'slug' => $article->slug()?->toString(),
                'content' => $article->content()->getRaw(),
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
        $this->connection->delete('articles', ['id' => $id->toString()]);
    }

    public function getById(ArticleId $id): ?Article
    {
        $row = $this->connection->fetchAssociative(
            'SELECT * FROM articles WHERE id = ?',
            [$id->toString()]
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
        // Fetch only published articles from database
        $rows = $this->connection->fetchAllAssociative(
            "SELECT * FROM articles WHERE status = 'published' ORDER BY created_at DESC"
        );

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

        $markdownContent = new \Blog\Domain\Shared\Markdown\MarkdownContent($row['content']);

        return Article::reconstitute(
            ArticleId::fromString((string) $row['id']),
            \Blog\Domain\Blog\ValueObject\Title::fromString($row['title']),
            $markdownContent,
            \Blog\Domain\Blog\ValueObject\AuthorId::fromString($row['author_id']),
            \Blog\Domain\Blog\ValueObject\ArticleStatus::fromString($row['status']),
            new \DateTimeImmutable($row['created_at']),
            new \DateTimeImmutable($row['updated_at']),
            $row['slug'] ? \Blog\Domain\Blog\ValueObject\Slug::fromString($row['slug']) : null,
            $category,
            null, // excerpt - would need to be hydrated from database if available
            null, // featuredImage - would need to be hydrated from database if available
            null, // metaDescription - would need to be hydrated from database if available
            0, // viewCount - would need to be hydrated from database if available
            isset($row['published_at']) && $row['published_at'] ? new \DateTimeImmutable($row['published_at']) : null,
            null, // scheduledAt - would need to be hydrated from database if available
            $tagsData // Use already hydrated tags
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

        return $this->hydrateBatch($rows);
    }

    public function getByCategory(CategoryId $categoryId): array
    {
        $rows = $this->connection->fetchAllAssociative(
            "SELECT * FROM articles WHERE category_id = ? AND status = 'published' ORDER BY created_at DESC",
            [$categoryId->toString()]
        );

        return $this->hydrateBatch($rows);
    }

    public function getRecentArticles(int $limit = 10): array
    {
        $rows = $this->connection->fetchAllAssociative(
            "SELECT * FROM articles ORDER BY created_at DESC LIMIT ?",
            [$limit],
            [ParameterType::INTEGER]
        );

        return $this->hydrateBatch($rows);
    }

    public function count(array $filters = []): int
    {
        $where = [];
        $params = [];
        $types = [];

        if (!empty($filters['status'])) {
            $where[] = 'status = ?';
            $params[] = $filters['status'];
            $types[] = ParameterType::STRING;
        }

        if (!empty($filters['start_date'])) {
            $where[] = 'created_at >= ?';
            $params[] = $filters['start_date'];
            $types[] = ParameterType::STRING;
        }

        if (!empty($filters['end_date'])) {
            $where[] = 'created_at <= ?';
            $params[] = $filters['end_date'];
            $types[] = ParameterType::STRING;
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $sql = "SELECT COUNT(*) FROM articles {$whereClause}";

        return (int) $this->connection->fetchOne($sql, $params, $types);
    }

    public function getCategories(): array
    {
        return [];
    }

    private function loadTagsForArticle(string $articleId): array
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
        // Single article hydration - still requires individual queries for category/tags
        // This is acceptable for single-article lookups (getById, getBySlug)
        // For bulk operations, use hydrateBatch instead
        $category = null;
        $tags = [];

        if (!empty($row['category_id'])) {
            $categoryData = $this->connection->fetchAssociative(
                'SELECT * FROM categories WHERE id = ?',
                [$row['category_id']]
            );
            $category = $categoryData ?: null;
        }

        $tags = $this->loadTagsForArticle((string) $row['id']);

        return $this->hydrateWithPreloadedData($row, $category, $tags);
    }

    /**
     * Batch hydration with preloaded categories and tags to avoid N+1 queries.
     *
     * @param array<int, array<string, mixed>> $rows
     *
     * @return Article[]
     */
    private function hydrateBatch(array $rows): array
    {
        if (empty($rows)) {
            return [];
        }

        $articleIds = array_column($rows, 'id');
        $categoryIds = array_filter(array_unique(array_column($rows, 'category_id')));

        // Load all categories at once
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

        // Load all tags for these articles at once
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

        // Hydrate with preloaded data
        return array_map(function ($row) use ($categories, $tagsByArticle) {
            return $this->hydrateWithPreloadedData(
                $row,
                $categories[$row['category_id'] ?? ''] ?? null,
                $tagsByArticle[$row['id']] ?? []
            );
        }, $rows);
    }
}
