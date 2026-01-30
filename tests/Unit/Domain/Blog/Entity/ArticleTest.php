<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Blog\Entity;

use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\ArticleStatus;
use Blog\Domain\Blog\ValueObject\Content;
use Blog\Domain\Blog\ValueObject\Title;
use Blog\Domain\User\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

final class ArticleTest extends TestCase
{
    public function test_creates_new_article_with_draft_status(): void
    {
        $title = Title::fromString('Môj článok');
        $content = Content::fromString('Toto je obsah článku.');
        $authorId = UserId::fromInt(1);

        $article = Article::create($title, $content, $authorId);

        $this->assertInstanceOf(Article::class, $article);
        $this->assertSame('Môj článok', $article->title()->toString());
        $this->assertSame('Toto je obsah článku.', $article->content()->toString());
        $this->assertSame(1, $article->authorId()->toInt());
        $this->assertSame('draft', $article->status()->toString());
    }

    public function test_updates_article_title_and_content(): void
    {
        $article = Article::create(
            Title::fromString('Starý titulok'),
            Content::fromString('Starý obsah článku.'),
            UserId::fromInt(1)
        );

        $newTitle = Title::fromString('Nový titulok');
        $newContent = Content::fromString('Nový obsah článku.');

        $article->update($newTitle, $newContent);

        $this->assertSame('Nový titulok', $article->title()->toString());
        $this->assertSame('Nový obsah článku.', $article->content()->toString());
    }

    public function test_publishes_draft_article(): void
    {
        $article = Article::create(
            Title::fromString('Môj článok'),
            Content::fromString('Toto je obsah článku.'),
            UserId::fromInt(1)
        );

        $this->assertSame('draft', $article->status()->toString());

        $article->publish();

        $this->assertSame('published', $article->status()->toString());
    }

    public function test_archives_published_article(): void
    {
        $article = Article::create(
            Title::fromString('Môj článok'),
            Content::fromString('Toto je obsah článku.'),
            UserId::fromInt(1)
        );

        $article->publish();
        $article->archive();

        $this->assertSame('archived', $article->status()->toString());
    }

    public function test_is_owned_by_returns_true_for_author(): void
    {
        $authorId = UserId::fromInt(1);
        $article = Article::create(
            Title::fromString('Môj článok'),
            Content::fromString('Toto je obsah článku.'),
            $authorId
        );

        $this->assertTrue($article->isOwnedBy($authorId));
    }

    public function test_is_owned_by_returns_false_for_different_user(): void
    {
        $article = Article::create(
            Title::fromString('Môj článok'),
            Content::fromString('Toto je obsah článku.'),
            UserId::fromInt(1)
        );

        $otherUserId = UserId::fromInt(2);

        $this->assertFalse($article->isOwnedBy($otherUserId));
    }

    public function test_reconstitute_creates_article_from_persistence(): void
    {
        $article = Article::reconstitute(
            ArticleId::fromInt(1),
            Title::fromString('Môj článok'),
            Content::fromString('Toto je obsah článku.'),
            UserId::fromInt(1),
            ArticleStatus::fromString('published'),
            new \DateTimeImmutable('2024-01-01 10:00:00'),
            new \DateTimeImmutable('2024-01-02 15:30:00')
        );

        $this->assertSame(1, $article->id()->toInt());
        $this->assertSame('Môj článok', $article->title()->toString());
        $this->assertSame('published', $article->status()->toString());
        $this->assertSame('2024-01-01 10:00:00', $article->createdAt()->format('Y-m-d H:i:s'));
        $this->assertSame('2024-01-02 15:30:00', $article->updatedAt()->format('Y-m-d H:i:s'));
    }
}
