<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Persistence\Doctrine;

use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;
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
                'user_id' => $article->authorId()->toBytes(),  // ← user_id as bytes
                'status' => $article->status()->toString(),
                'created_at' => $article->createdAt()->format('Y-m-d H:i:s'),
                'updated_at' => $article->updatedAt()->format('Y-m-d H:i:s'),
                'category' => null,
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
                'user_id' => $article->authorId()->toBytes(),  // ← user_id as bytes
                'status' => $article->status()->toString(),
                'updated_at' => $article->updatedAt()->format('Y-m-d H:i:s'),
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
        $rows = $this->connection->fetchAllAssociative(
            'SELECT * FROM articles ORDER BY created_at DESC'
        );

        return array_map([$this, 'hydrate'], $rows);
    }

    public function search(string $query): array
    {
        if (empty(trim($query))) {
            return [];
        }

        // Split query into terms and add * wildcard to each for prefix matching
        $terms = array_filter(explode(' ', trim($query)));
        $ftsQuery = implode(' ', array_map(fn($term) => $term . '*', $terms));

        // Use FTS5 virtual table for full-text search
        $sql = "
            SELECT a.*
            FROM articles a
            INNER JOIN articles_fts fts ON a.id = fts.rowid
            WHERE articles_fts MATCH :query
            ORDER BY rank
            LIMIT 50
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('query', $ftsQuery);
        $result = $stmt->executeQuery();

        return array_map(
            [$this, 'hydrate'],
            $result->fetchAllAssociative()
        );
    }

    public function getCategories(): array
    {
        return [];
    }

    private function hydrate(array $row): Article
    {
        return Article::reconstitute(
            ArticleId::fromInt((int) $row['id']),
            \Blog\Domain\Blog\ValueObject\Title::fromString($row['title']),
            \Blog\Domain\Blog\ValueObject\Content::fromString($row['content']),
            \Blog\Domain\User\ValueObject\UserId::fromBytes($row['user_id']), // ← user_id from bytes
            \Blog\Domain\Blog\ValueObject\ArticleStatus::fromString($row['status']),
            new \DateTimeImmutable($row['created_at']),
            new \DateTimeImmutable($row['updated_at']),
            $row['slug'] ? \Blog\Domain\Blog\ValueObject\Slug::fromString($row['slug']) : null
        );
    }
}
