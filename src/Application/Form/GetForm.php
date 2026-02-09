<?php

declare(strict_types=1);

namespace Blog\Application\Form;

use Blog\Domain\Form\Entity\Form;
use Blog\Domain\Form\Repository\FormRepositoryInterface;

final readonly class GetForm
{
    public function __construct(
        private FormRepositoryInterface $formRepository
    ) {}

    public function bySlug(string $slug): ?Form
    {
        return $this->formRepository->getBySlug($slug);
    }
}
