<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Blog;

use Blog\Application\Blog\GetArticleBySlug;
use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\ArticleStatus;
use Blog\Domain\Blog\ValueObject\AuthorId;
use Blog\Domain\Blog\ValueObject\Title;
use Blog\Domain\Shared\Markdown\MarkdownContent;
use PHPUnit\Framework\TestCase;

final class GetArticleBySlugHandlerTest extends TestCase
{
    private $repository;
    private GetArticleBySlug $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ArticleRepository::class);
        $this->handler = new GetArticleBySlug($this->repository);
    }

    public function test_returns_article_by_slug(): void
    {
        // Arrange
        $slug = 'test-article';
        $article = $this->createMock(Article::class);
        $article->method('getId')->willReturn(ArticleId::fromInt(1));
        $article->method('getTitle')->willReturn(Title::fromString('Test Article'));
        $article->method('getSlug')->willReturn(new \Blog\Domain\Blog\ValueObject\Slug($slug));
        $article->method('getContent')->willReturn(new MarkdownContent('Test content'));
        $article->method('getStatus')->willReturn(ArticleStatus::fromString('published'));
        $article->method('getAuthorId')->willReturn(AuthorId::fromString('550e8400-e29b-41d4-a716-446655440000'));
        $article->method('getCreatedAt')->willReturn(new \DateTimeImmutable('2024-01-01'));
        $article->method('getUpdatedAt')->willReturn(new \DateTimeImmutable('2024-01-01'));

        $this->repository->expects($this->once())
            ->method('getBySlug')
            ->willReturn($article);

        // Act
        $result = $this->handler->execute(['slug' => $slug]);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('article', $result['data']);
        $this->assertEquals($slug, $result['data']['article']['slug']);
    }

    public function test_throws_exception_when_article_not_found(): void
    {
        // Arrange
        $this->repository->expects($this->once())
            ->method('getBySlug')
            ->willReturn(null);

        // Assert
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Article not found');

        // Act
        $this->handler->execute(['slug' => 'non-existent']);
    }

    public function test_validates_empty_slug(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Slug is required');

        $this->handler->execute(['slug' => '']);
    }

    public function test_validates_slug_too_long(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Slug must not exceed 255 characters');

        $this->handler->execute(['slug' => str_repeat('a', 256)]);
    }

    public function test_validates_slug_format(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Slug can only contain lowercase letters, numbers, and hyphens');

        $this->handler->execute(['slug' => 'Invalid_Slug']);
    }
}
