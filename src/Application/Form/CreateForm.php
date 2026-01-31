<?php

declare(strict_types=1);

namespace Blog\Application\Form;

use Blog\Domain\Form\Entity\Form;
use Blog\Domain\Form\Repository\FormRepositoryInterface;

final readonly class CreateForm
{
    public function __construct(
        private FormRepositoryInterface $formRepository
    ) {
    }

    /**
     * @param array<int, mixed> $fields
     */
    public function execute(string $title, string $slug, array $fields): Form
    {
        // Here we could check if slug exists, etc.

        $form = Form::create($title, $slug, $fields);

        $this->formRepository->save($form);

        return $form;
    }
}
