<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Domain\Blog\Repository\TagRepository;

final readonly class GetAllTags
{
    public function __construct(private TagRepository $tagRepository)
    {
    }

    /**
     * @return \Blog\Domain\Blog\Entity\Tag[]
     */
    public function __invoke(): array
    {
        return $this->tagRepository->getAll();
    }
}
