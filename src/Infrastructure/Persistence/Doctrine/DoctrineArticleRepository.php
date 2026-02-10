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
        // Fetch ALL articles - no business logic filtering in repository
        $rows = $this->connection->fetchAllAssociative(
            "SELECT * FROM articles ORDER BY created_at DESC"
        );

        return $this->hydrateBatch($rows);
    }

    public function findPublished(): array
    {
        // Optimized query with LEFT JOINs to fetch articles with categories in single query
        $sql = "
            SELECT 
                a.*,
                c.id as category_id,
                c.name as category_name,
                c.slug as category_slug,
                c.description as category_description,
                c.created_at as category_created_at,
                c.updated_at as category_updated_at
            FROM articles a
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published'
            ORDER BY a.created_at DESC
        ";

        $rows = $this->connection->fetchAllAssociative($sql);

        // Tags still loaded separately (many-to-many doesn't work well with single JOIN)
        $articleIds = array_column($rows, 'id');
        $tagsByArticle = $this->loadTagsForArticles($articleIds);

        return array_map(function ($row) use ($tagsByArticle) {
            $categoryData = $row['category_id'] ? [
                'id' => $row['category_id'],
                'name' => $row['category_name'],
                'slug' => $row['category_slug'],
                'description' => $row['category_description'],
                'created_at' => $row['category_created_at'],
                'updated_at' => $row['category_updated_at'],
            ] : null;

            return $this->hydrateWithPreloadedData(
                $row,
                $categoryData,
                $tagsByArticle[$row['id']] ?? []
            );
        }, $rows);
    }

    public function findByStatus(string $status): array
    {
        // Specific query for articles by status - filtering done in SQL
        $rows = $this->connection->fetchAllAssociative(
            "SELECT * FROM articles WHERE status = ? ORDER BY created_at DESC",
            [$status]
        );

        return $this->hydrateBatch($rows);
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

        $data = new \Blog\Domain\Blog\ValueObject\ArticleReconstitutionData(
            id: ArticleId::fromString((string) $row['id']),
            title: \Blog\Domain\Blog\ValueObject\Title::fromString($row['title']),
            content: $markdownContent,
            authorId: \Blog\Domain\Blog\ValueObject\AuthorId::fromString($row['author_id']),
            status: \Blog\Domain\Blog\ValueObject\ArticleStatus::fromString($row['status']),
            createdAt: new \DateTimeImmutable($row['created_at']),
            updatedAt: new \DateTimeImmutable($row['updated_at']),
            slug: $row['slug'] ? \Blog\Domain\Blog\ValueObject\Slug::fromString($row['slug']) : null,
            category: $category,
            excerpt: null, // would need to be hydrated from database if available
            featuredImage: null, // would need to be hydrated from database if available
            metaDescription: null, // would need to be hydrated from database if available
            viewCount: 0, // would need to be hydrated from database if available
            publishedAt: isset($row['published_at']) && $row['published_at'] ? new \DateTimeImmutable($row['published_at']) : null,
            scheduledAt: null, // would need to be hydrated from database if available
            tagIds: $tagsData
        );

        return Article::reconstitute($data);
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

    private function hydrate(array $row): Article
    {
        // Single article hydration with preloaded category/tags
        // Uses IN clause with single item to reuse batch loading logic
        return $this->hydrateBatch([$row])[0] ?? throw new \RuntimeException('Failed to hydrate article');
    }

    /**
     * Load tags for multiple articles in a single query.
     *
     * @param array<int|string> $articleIds
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    private function loadTagsForArticles(array $articleIds): array
    {
        if (empty($articleIds)) {
            return [];
        }

        $tagsData = $this->connection->fetchAllAssociative(
            'SELECT t.*, at.article_id 
             FROM tags t
             INNER JOIN article_tags at ON t.id = at.tag_id
             WHERE at.article_id IN (?)
             ORDER BY t.name ASC',
            [$articleIds],
            [\Doctrine\DBAL\ArrayParameterType::STRING]
        );

        $tagsByArticle = [];
        foreach ($tagsData as $tag) {
            $tagsByArticle[$tag['article_id']][] = $tag;
        }

        return $tagsByArticle;
    }

    /**
     * Batch hydration with preloaded categories and tags to avoid N+1 queries.
     * Uses IN clause queries for efficient batch loading.
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

        // Load all categories at once using IN clause
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

        // Load all tags at once using optimized method
        $tagsByArticle = $this->loadTagsForArticles($articleIds);

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
