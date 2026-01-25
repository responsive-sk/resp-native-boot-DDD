<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\Event;

use Blog\Domain\Common\DomainEvent;
use Blog\Domain\Blog\ValueObject\ArticleId;
use DateTimeImmutable;

final readonly class ArticlePublishedEvent implements DomainEvent
{
    private DateTimeImmutable $occurredOn;

    public function __construct(
        private ArticleId $articleId
    ) {
        $this->occurredOn = new DateTimeImmutable();
    }

    public function articleId(): ArticleId
    {
        return $this->articleId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function eventName(): string
    {
        return 'article.published';
    }
}

