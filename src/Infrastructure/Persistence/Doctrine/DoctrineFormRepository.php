<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Persistence\Doctrine;

use Blog\Domain\Form\Entity\Form;
use Blog\Domain\Form\Repository\FormRepositoryInterface;
use Blog\Domain\Form\ValueObject\FormId;
use Doctrine\DBAL\Connection;

final class DoctrineFormRepository implements FormRepositoryInterface
{
    /** @var Connection @phpstan-ignore property.onlyWritten */
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(Form $form): void
    {
        // In a real implementation, this would perform an INSERT or UPDATE.
        // For example:
        // $this->connection->executeStatement(
        //     'INSERT INTO forms (id, title, slug, fields, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)',
        //     [
        //         $form->id()->toString(),
        //         $form->title(),
        //         $form->slug(),
        //         json_encode($form->fields()),
        //         $form->createdAt()->format('Y-m-d H:i:s'),
        //         $form->updatedAt()->format('Y-m-d H:i:s'),
        //     ]
        // );
    }

    public function getById(FormId $id): ?Form
    {
        // In a real implementation, this would perform a SELECT by ID.
        // The data would then be used to reconstitute the Form entity.
        return null;
    }

    public function getBySlug(string $slug): ?Form
    {
        // In a real implementation, this would perform a SELECT by slug.
        return null;
    }

    /**
     * @return Form[]
     */
    public function getAll(): array
    {
        // In a real implementation, this would select all forms.
        return [];
    }

    public function delete(FormId $id): void
    {
        // In a real implementation, this would perform a DELETE statement.
    }
}
