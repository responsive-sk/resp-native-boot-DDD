<?php

declare(strict_types=1);

namespace Blog\Application\Blog;

use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\TagRepository;

final readonly class ManageArticleTags
{
    public function __construct(
        private TagRepository $tagRepository,
        private GetOrCreateTag $getOrCreateTag
    ) {}

    /**
     * Set tags for an article from array of tag names
     */
    public function setTags(Article $article, array $tagNames): void
    {
        // Clear existing tags
        $existingTags = $this->tagRepository->getByArticleId($article->id()->toInt());
        foreach ($existingTags as $tag) {
            $this->tagRepository->removeFromArticle($article->id()->toInt(), $tag->id());
        }

        // Add new tags
        $newTags = [];
        foreach ($tagNames as $tagName) {
            if (empty(trim($tagName))) {
                continue;
            }

            $tag = ($this->getOrCreateTag)(trim($tagName));
            $this->tagRepository->addToArticle($article->id()->toInt(), $tag->id());
            $newTags[] = $tag;
        }

        // Update article entity
        $article->setTags($newTags);
    }

    /**
     * Get tags for an article
     * 
     * @return \Blog\Domain\Blog\Entity\Tag[]
     */
    public function getTags(Article $article): array
    {
        return $this->tagRepository->getByArticleId($article->id()->toInt());
    }

    /**
     * Parse tag string (comma or space separated) into array
     */
    public function parseTagString(string $tagString): array
    {
        if (empty(trim($tagString))) {
            return [];
        }

        // Split by comma or semicolon, clean up whitespace
        $tags = preg_split('/[,\s;]+/', trim($tagString));
        
        return array_filter(array_unique(array_map('trim', $tags)));
    }
}
