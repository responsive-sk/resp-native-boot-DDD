<?php
// src/Domain/Blog/Entity/Article.php - S AUTHORID
declare(strict_types=1);

namespace Blog\Domain\Blog\Entity;

use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\Title;
use Blog\Domain\Blog\ValueObject\AuthorId;
use Blog\Domain\Shared\Markdown\MarkdownContent;
use Blog\Domain\Blog\ValueObject\ArticleStatus;
use Blog\Domain\Blog\ValueObject\Slug;
use Blog\Domain\Shared\ValueObject\DateTimeValue;
use DateTimeImmutable;

final class Article
{
    private DateTimeValue $createdAt;
    private DateTimeValue $updatedAt;
    private ?DateTimeValue $publishedAt = null;
    private ?DateTimeValue $scheduledAt = null;
    
    private ?string $excerpt = null;

    public function __construct(
        private readonly ArticleId $id,
        private Title $title,
        private MarkdownContent $content,
        private Slug $slug,
        private readonly AuthorId $authorId,
        private ArticleStatus $status = ArticleStatus::DRAFT,
        private ?Category $category = null,
        private ?string $featuredImage = null,
        private ?string $metaDescription = null,
        private int $viewCount = 0,
        private array $tagIds = [],
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
        ?DateTimeImmutable $publishedAt = null,
        ?DateTimeImmutable $scheduledAt = null
    ) {
        $this->createdAt = $createdAt 
            ? DateTimeValue::fromString($createdAt->format('Y-m-d H:i:s'))
            : DateTimeValue::now();
        
        $this->updatedAt = $updatedAt 
            ? DateTimeValue::fromString($updatedAt->format('Y-m-d H:i:s'))
            : clone $this->createdAt;
        
        if ($publishedAt !== null) {
            $this->publishedAt = DateTimeValue::fromString($publishedAt->format('Y-m-d H:i:s'));
        }
        
        if ($scheduledAt !== null) {
            $this->scheduledAt = DateTimeValue::fromString($scheduledAt->format('Y-m-d H:i:s'));
        }
        
        // Auto-generate excerpt
        $this->excerpt = $content->getExcerpt(200);
        
        if ($this->status === ArticleStatus::PUBLISHED && $this->publishedAt === null) {
            $this->publishedAt = DateTimeValue::now();
        }
    }

    // Getters
    public function getId(): ArticleId { return $this->id; }
    public function getTitle(): Title { return $this->title; }
    public function getContent(): MarkdownContent { return $this->content; }
    public function getSlug(): Slug { return $this->slug; }
    public function getStatus(): ArticleStatus { return $this->status; }
    public function getAuthorId(): AuthorId { return $this->authorId; }
    public function getCategory(): ?Category { return $this->category; }
    public function getExcerpt(): string { return $this->excerpt ?? $this->content->getExcerpt(200); }
    public function getFeaturedImage(): ?string { return $this->featuredImage; }
    public function getMetaDescription(): ?string { return $this->metaDescription; }
    public function getViewCount(): int { return $this->viewCount; }
    public function getCreatedAt(): DateTimeValue { return $this->createdAt; }
    public function getUpdatedAt(): DateTimeValue { return $this->updatedAt; }
    public function getPublishedAt(): ?DateTimeValue { return $this->publishedAt; }
    public function getScheduledAt(): ?DateTimeValue { return $this->scheduledAt; }

    // Domain-style accessors - delegate to getters to avoid duplication
    public function id(): ArticleId { return $this->getId(); }
    public function title(): Title { return $this->getTitle(); }
    public function content(): MarkdownContent { return $this->getContent(); }
    public function slug(): Slug { return $this->getSlug(); }
    public function status(): ArticleStatus { return $this->getStatus(); }
    public function authorId(): AuthorId { return $this->getAuthorId(); }
    public function category(): ?Category { return $this->getCategory(); }
    public function excerpt(): string { return $this->getExcerpt(); }
    public function featuredImage(): ?string { return $this->getFeaturedImage(); }
    public function metaDescription(): ?string { return $this->getMetaDescription(); }
    public function viewCount(): int { return $this->getViewCount(); }
    public function createdAt(): DateTimeValue { return $this->getCreatedAt(); }
    public function updatedAt(): DateTimeValue { return $this->getUpdatedAt(); }
    public function publishedAt(): ?DateTimeValue { return $this->getPublishedAt(); }
    public function scheduledAt(): ?DateTimeValue { return $this->getScheduledAt(); }
    
    public function isOwnedBy(AuthorId $authorId): bool
    {
        return $this->authorId->toString() === $authorId->toString();
    }
    
    /** @return array<int> */
    public function getTagIds(): array { return $this->tagIds; }
    
    // Business methods
    public function updateTitle(Title $title): void
    {
        $this->title = $title;
        $this->updatedAt = DateTimeValue::now();
    }
    
    public function updateContent(MarkdownContent $content): void
    {
        $this->content = $content;
        $this->updatedAt = DateTimeValue::now();

        // Update excerpt if it was auto-generated
        if ($this->excerpt === $this->content->getExcerpt(200)) {
            $this->excerpt = $content->getExcerpt(200);
        }
    }
    
    public function updateSlug(Slug $slug): void
    {
        $this->slug = $slug;
        $this->updatedAt = DateTimeValue::now();
    }
    
    public function update(Title $title, MarkdownContent $content): void
    {
        $this->updateTitle($title);
        $this->updateContent($content);
    }
    
    public function publish(): void
    {
        if ($this->status === ArticleStatus::PUBLISHED) {
            return;
        }
        
        $this->status = ArticleStatus::PUBLISHED;
        $this->publishedAt = DateTimeValue::now();
        $this->updatedAt = DateTimeValue::now();
    }
    
    public function unpublish(): void
    {
        $this->status = ArticleStatus::DRAFT;
        $this->publishedAt = null;
        $this->updatedAt = DateTimeValue::now();
    }
    
    public function archive(): void
    {
        $this->status = ArticleStatus::ARCHIVED;
        $this->updatedAt = DateTimeValue::now();
    }
    
    public function schedule(DateTimeImmutable $publishDate): void
    {
        $this->status = ArticleStatus::SCHEDULED;
        $this->scheduledAt = DateTimeValue::fromString($publishDate->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTimeValue::now();
    }
    
    public function updateCategory(?Category $category): void
    {
        $this->category = $category;
        $this->updatedAt = DateTimeValue::now();
    }
    
    public function addTag(int $tagId): void
    {
        if (!in_array($tagId, $this->tagIds, true)) {
            $this->tagIds[] = $tagId;
            $this->updatedAt = DateTimeValue::now();
        }
    }
    
    public function removeTag(int $tagId): void
    {
        $key = array_search($tagId, $this->tagIds, true);
        if ($key !== false) {
            unset($this->tagIds[$key]);
            $this->tagIds = array_values($this->tagIds);
            $this->updatedAt = DateTimeValue::now();
        }
    }
    
    public function incrementViewCount(): void
    {
        $this->viewCount++;
        $this->updatedAt = DateTimeValue::now();
    }
    
    public function updateFeaturedImage(?string $featuredImage): void
    {
        $this->featuredImage = $featuredImage;
        $this->updatedAt = DateTimeValue::now();
    }
    
    public function updateMetaDescription(?string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
        $this->updatedAt = DateTimeValue::now();
    }
    
    public function isPublished(): bool
    {
        return $this->status === ArticleStatus::PUBLISHED;
    }
    
    public function isDraft(): bool
    {
        return $this->status === ArticleStatus::DRAFT;
    }
    
    public function isScheduled(): bool
    {
        return $this->status === ArticleStatus::SCHEDULED;
    }
    
    public function isArchived(): bool
    {
        return $this->status === ArticleStatus::ARCHIVED;
    }
    
    public function canEdit(): bool
    {
        return $this->status->canEdit();
    }

    public function __toString(): string
    {
        return sprintf('[Article %s] %s', $this->id->getValue(), $this->title->getValue());
    }

    /**
     * Factory method for reconstituting an Article from persistence.
     * Uses ArticleReconstitutionData DTO to avoid excessive parameter count.
     */
    public static function reconstitute(\Blog\Domain\Blog\ValueObject\ArticleReconstitutionData $data): self
    {
        $article = new self(
            $data->id,
            $data->title,
            $data->content,
            $data->slug ?? Slug::fromString($data->title->value),
            $data->authorId,
            $data->status,
            $data->category,
            $data->featuredImage,
            $data->metaDescription,
            $data->viewCount,
            $data->tagIds,
            $data->createdAt,
            $data->updatedAt,
            $data->publishedAt,
            $data->scheduledAt
        );

        $article->updatedAt = DateTimeValue::fromString($data->updatedAt->format('Y-m-d H:i:s'));
        $article->featuredImage = $data->featuredImage;
        $article->metaDescription = $data->metaDescription;
        $article->viewCount = $data->viewCount;
        $article->tagIds = $data->tagIds;
        $article->excerpt = $data->excerpt;

        if ($data->publishedAt) {
            $article->publishedAt = DateTimeValue::fromString($data->publishedAt->format('Y-m-d H:i:s'));
        }

        if ($data->scheduledAt) {
            $article->scheduledAt = DateTimeValue::fromString($data->scheduledAt->format('Y-m-d H:i:s'));
        }

        return $article;
    }

    public static function create(
        Title $title,
        MarkdownContent $content,
        AuthorId $authorId,
        ?Category $category = null
    ): self {
        return new self(
            ArticleId::generate(),
            $title,
            $content,
            Slug::fromString($title->value),
            $authorId,
            ArticleStatus::DRAFT,
            $category
        );
    }
}