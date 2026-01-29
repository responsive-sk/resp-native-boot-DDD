<?php

declare(strict_types=1);

namespace Blog\Domain\Form\Repository;

use Blog\Domain\Form\Entity\Form;
use Blog\Domain\Form\ValueObject\FormId;

interface FormRepositoryInterface
{
    public function save(Form $form): void;

    public function getById(FormId $id): ?Form;

    public function getBySlug(string $slug): ?Form;

    /**
     * @return Form[]
     */
    public function getAll(): array;

    public function delete(FormId $id): void;
}
