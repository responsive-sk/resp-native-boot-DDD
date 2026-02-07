<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Persistence\Doctrine;

use Blog\Domain\Blog\Entity\Tag;
use Blog\Domain\Blog\Repository\TagRepository;
use Blog\Domain\Blog\ValueObject\TagId;
use Blog\Domain\Blog\ValueObject\TagSlug;
use Doctrine\DBAL\Connection;

final class DoctrineTagRepository implements TagRepository
{
    public function __construct(private Connection $connection)
    {
    }

    public function add(Tag $tag): void
    {
        $this->connection->insert('tags', [
            'id' => $tag->id()?->toString(),
            'name' => $tag->name()->toString(),
            'slug' => $tag->slug()->toString(),
            'created_at' => $tag->createdAt()->format('Y-m-d H:i:s'),
        ]);

        $tagId = TagId::fromString($this->connection->lastInsertId());
        $tag->setId($tagId);
    }

    public function remove(TagId $id): void
    {
        $this->connection->delete('tags', [
            'id' => $id->toString(),
        ]);
    }

    public function getById(TagId $id): ?Tag
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('tags')
            ->where('id = :id')
            ->setParameter('id', $id->toString())
            ->fetchAssociative();

        if (!$result) {
            return null;
        }

        return $this->hydrateTag($result);
    }

    public function getBySlug(TagSlug $slug): ?Tag
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('tags')
            ->where('slug = :slug')
            ->setParameter('slug', $slug->toString())
            ->fetchAssociative();

        if (!$result) {
            return null;
        }

        return $this->hydrateTag($result);
    }

    public function getAll(): array
    {
        $qb = $this->connection->createQueryBuilder();
        $results = $qb
            ->select('*')
            ->from('tags')
            ->orderBy('name', 'ASC')
            ->fetchAllAssociative();

        return array_map([$this, 'hydrateTag'], $results);
    }

    public function nameExists(string $name, ?TagId $excludeId = null): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('COUNT(*)')
            ->from('tags')
            ->where('LOWER(name) = LOWER(:name)')
            ->setParameter('name', $name);

        if ($excludeId !== null) {
            $qb
                ->andWhere('id != :exclude_id')
                ->setParameter('exclude_id', $excludeId->toString());
        }

        $count = (int) $qb->fetchOne();

        return $count > 0;
    }

    public function getByArticleId(int $articleId): array
    {
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
    }

    public function addToArticle(int $articleId, TagId $tagId): void
    {
        $this->connection->insert('article_tags', [
            'article_id' => $articleId,
            'tag_id' => $tagId->toString(),
        ]);
    }

    public function removeFromArticle(int $articleId, TagId $tagId): void
    {
        $this->connection->delete('article_tags', [
            'article_id' => $articleId,
            'tag_id' => $tagId->toString(),
        ]);
    }

    public function getOrCreateByName(string $name): Tag
    {
        // Try to find existing tag
        $qb = $this->connection->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from('tags')
            ->where('LOWER(name) = LOWER(:name)')
            ->setParameter('name', $name)
            ->fetchAssociative();

        if ($result) {
            return $this->hydrateTag($result);
        }

        // Create new tag
        $tagName = \Blog\Domain\Blog\ValueObject\TagName::fromString($name);
        $tag = Tag::create($tagName);

        $this->add($tag);

        return $tag;
    }

    private function hydrateTag(array $data): Tag
    {
        return Tag::reconstitute(
            TagId::fromString($data['id']),
            \Blog\Domain\Blog\ValueObject\TagName::fromString($data['name']),
            TagSlug::fromString($data['slug']),
            new \DateTimeImmutable($data['created_at'])
        );
    }
}
