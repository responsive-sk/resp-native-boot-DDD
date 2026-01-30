<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\Entity;

use Blog\Domain\Blog\Event\ArticlePublishedEvent;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\ArticleStatus;
use Blog\Domain\Blog\ValueObject\Content;
use Blog\Domain\Blog\ValueObject\Slug;
use Blog\Domain\Blog\ValueObject\Title;
use Blog\Domain\User\ValueObject\UserId;
use DateTimeImmutable;

final class Article
{
    /** @var array<int, \Blog\Domain\Common\DomainEvent> */
    private array $domainEvents = [];
    private function __construct(
        private ?ArticleId        $id,
        private Title             $title,
        private Content           $content,
        private readonly UserId   $authorId,
        private ArticleStatus     $status,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
        private ?Slug             $slug = null
    ) {
    }

    public static function create(
        Title $title,
        Content $content,
        UserId $authorId
    ): self {
        $now = new DateTimeImmutable();

        return new self(
            id: null,
            title: $title,
            content: $content,
            authorId: $authorId,
            status: ArticleStatus::draft(),
            createdAt: $now,
            updatedAt: $now,
            slug: Slug::fromString($title->toString())
        );
    }

    public static function reconstitute(
        ArticleId         $id,
        Title             $title,
        Content           $content,
        UserId            $authorId,
        ArticleStatus     $status,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        ?Slug             $slug = null
    ): self {
        return new self($id, $title, $content, $authorId, $status, $createdAt, $updatedAt, $slug);
    }

    public function update(Title $title, Content $content): void
    {
        $this->title = $title;
        $this->content = $content;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function slug(): ?Slug
    {
        return $this->slug;
    }

    public function setSlug(Slug $slug): void
    {
        $this->slug = $slug;
    }

    public function publish(): void
    {
        if ($this->status->isPublished()) {
            throw new \DomainException('Článok je už publikovaný');
        }

        $this->status = ArticleStatus::published();
        $this->updatedAt = new DateTimeImmutable();

        // Event vytvoríme len ak máme ID (po uložení do DB)
        if ($this->id !== null) {
            $this->recordEvent(new ArticlePublishedEvent($this->id));
        }
    }

    public function archive(): void
    {
        $this->status = ArticleStatus::archived();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function isOwnedBy(UserId $userId): bool
    {
        return $this->authorId->equals($userId);
    }

    // Getters
    public function id(): ?ArticleId
    {
        return $this->id;
    }
    public function title(): Title
    {
        return $this->title;
    }
    public function content(): Content
    {
        return $this->content;
    }
    public function authorId(): UserId
    {
        return $this->authorId;
    }
    public function status(): ArticleStatus
    {
        return $this->status;
    }
    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    // Public slug/id for the article used by templates. Prefers slug string, falls back to id string,
    // and finally returns empty string if neither is available.
    public function getUri(): string
    {
        $slug = $this->slug();
        if ($slug !== null) {
            return trim($slug->toString(), '/');
        }

        $id = $this->id();
        if ($id !== null) {
            return (string) $id;
        }

        return '';
    }

    // Compatibility getters used by legacy templates. Prefer using title() and content() in new code.
    public function getTitle(): Title
    {
        return $this->title();
    }

    public function getContent(): Content
    {
        return $this->content();
    }

    // For persistence layer to set ID after insert
    public function setId(ArticleId $id): void
    {
        if ($this->id !== null) {
            throw new \LogicException('Article ID už bolo nastavené');
        }
        $this->id = $id;
    }

    // Domain Events
    private function recordEvent(\Blog\Domain\Common\DomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }

    /**
     * @return array<int, \App\Domain\Common\DomainEvent>
     */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];
        return $events;
    }
}
