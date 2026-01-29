<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Persistence\Doctrine;

use Blog\Domain\Form\Entity\Form;
use Blog\Domain\Form\Repository\FormRepositoryInterface;
use Blog\Domain\Form\ValueObject\FormId;
use Doctrine\DBAL\Connection;
use Exception;
use DateTimeImmutable;

final readonly class DoctrineFormRepository implements FormRepositoryInterface
{
    public function __construct(
        private Connection $connection
    ) {
        $this->ensureTableExists();
    }

    private function ensureTableExists(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['forms'])) {
            $sql = <<<SQL
                CREATE TABLE forms (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title TEXT NOT NULL,
                    slug TEXT NOT NULL UNIQUE,
                    fields TEXT NOT NULL, -- JSON
                    created_at TEXT NOT NULL,
                    updated_at TEXT NOT NULL
                )
SQL;
            $this->connection->executeStatement($sql);
        }
    }

    public function save(Form $form): void
    {
        $data = [
            'title' => $form->title(),
            'slug' => $form->slug(),
            'fields' => json_encode($form->fields()),
            'created_at' => $form->createdAt()->format('Y-m-d H:i:s'),
            'updated_at' => $form->updatedAt()->format('Y-m-d H:i:s'),
        ];

        if ($form->id() === null) {
            $this->connection->insert('forms', $data);
            $form->setId(FormId::fromInt((int) $this->connection->lastInsertId()));
        } else {
            $this->connection->update('forms', $data, ['id' => $form->id()->toInt()]);
        }
    }

    public function getById(FormId $id): ?Form
    {
        $data = $this->connection->fetchAssociative(
            'SELECT * FROM forms WHERE id = ?',
            [$id->toInt()]
        );

        return $data ? $this->mapToEntity($data) : null;
    }

    public function getBySlug(string $slug): ?Form
    {
        $data = $this->connection->fetchAssociative(
            'SELECT * FROM forms WHERE slug = ?',
            [$slug]
        );

        return $data ? $this->mapToEntity($data) : null;
    }

    /**
     * @return Form[]
     */
    public function getAll(): array
    {
        $rows = $this->connection->fetchAllAssociative('SELECT * FROM forms ORDER BY created_at DESC');

        return array_map(fn($row) => $this->mapToEntity($row), $rows);
    }

    public function delete(FormId $id): void
    {
        $this->connection->delete('forms', ['id' => $id->toInt()]);
    }

    private function mapToEntity(array $row): Form
    {
        try {
            return Form::reconstitute(
                FormId::fromInt((int) $row['id']),
                $row['title'],
                $row['slug'],
                json_decode($row['fields'], true) ?? [],
                new DateTimeImmutable($row['created_at']),
                new DateTimeImmutable($row['updated_at'])
            );
        } catch (Exception $e) {
            throw new \RuntimeException('Error mapping form entity: ' . $e->getMessage());
        }
    }
}
