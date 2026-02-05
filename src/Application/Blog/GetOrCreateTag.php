<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Domain\Blog\Entity\Tag;
use Blog\Domain\Blog\Repository\TagRepository;
use Blog\Domain\Blog\ValueObject\TagName;

final readonly class GetOrCreateTag
{
    public function __construct(private TagRepository $tagRepository) {}

    public function __invoke(string $name): Tag
    {
        return $this->tagRepository->getOrCreateByName($name);
    }
}
