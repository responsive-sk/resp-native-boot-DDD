<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Blog;

use Blog\Application\Blog\CreateArticle;
use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepositoryInterface;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\Slug;
use Blog\Domain\Blog\ValueObject\Title;
use Blog\Domain\Blog\ValueObject\Content;
use Blog\Domain\User\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

final class CreateArticleTest extends TestCase
{
    private CreateArticle $useCase;
    private $articleRepository;

    protected function setUp(): void
    {
        $this->articleRepository = $this->createMock(ArticleRepositoryInterface::class);
        $this->useCase = new CreateArticle($this->articleRepository);
    }

    public function test_creates_article_with_unique_slug(): void
    {
        $input = [
            'title' => 'Test Article',
            'content' => 'Test content for article',
            'author_id' => '123e4567-e89b-12d3-a456-426614174000'
        ];

        // Mock repository to return null for slug check (unique)
        $this->articleRepository->expects($this->once())
            ->method('getBySlug')
            ->with($this->callback(function ($slug) {
                return $slug->toString() === 'test-article';
            }))
            ->willReturn(null);

        // Mock repository add method
        $this->articleRepository->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($article) {
                return $article instanceof Article && 
                       $article->slug()->toString() === 'test-article';
            }));

        $result = $this->useCase->execute($input);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('article_id', $result['data']);
        $this->assertArrayHasKey('article', $result['data']);
    }

    public function test_creates_article_with_slug_suffix_when_duplicate(): void
    {
        $input = [
            'title' => 'Test Article',
            'content' => 'Test content for article',
            'author_id' => '123e4567-e89b-12d3-a456-426614174000'
        ];

        // Mock repository to simulate duplicate slug
        $this->articleRepository->expects($this->exactly(2))
            ->method('getBySlug')
            ->withConsecutive(
                [$this->callback(function ($slug) {
                    return $slug->toString() === 'test-article';
                })],
                [$this->callback(function ($slug) {
                    return $slug->toString() === 'test-article-1';
                })]
            )
            ->willReturnOnConsecutiveCalls(
                new Article(ArticleId::generate(), UserId::generate(), Title::fromString('Existing'), Content::fromString('Content'), new Slug('test-article')), // First call returns existing article
                null // Second call returns null (unique)
            );

        $this->articleRepository->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($article) {
                return $article instanceof Article && 
                       $article->slug()->toString() === 'test-article-1';
            }));

        $result = $this->useCase->execute($input);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('article_id', $result['data']);
    }

    public function test_handles_multiple_slug_duplicates(): void
    {
        $input = [
            'title' => 'Test Article',
            'content' => 'Test content for article',
            'author_id' => '123e4567-e89b-12d3-a456-426614174000'
        ];

        // Mock repository to simulate multiple duplicates
        $this->articleRepository->expects($this->exactly(3))
            ->method('getBySlug')
            ->withConsecutive(
                [$this->callback(function ($slug) {
                    return $slug->toString() === 'test-article';
                })],
                [$this->callback(function ($slug) {
                    return $slug->toString() === 'test-article-1';
                })],
                [$this->callback(function ($slug) {
                    return $slug->toString() === 'test-article-2';
                })]
            )
            ->willReturnOnConsecutiveCalls(
                new Article(ArticleId::generate(), UserId::generate(), Title::fromString('Existing'), Content::fromString('Content'), new Slug('test-article')),
                new Article(ArticleId::generate(), UserId::generate(), Title::fromString('Existing'), Content::fromString('Content'), new Slug('test-article-1')),
                null // Third call returns null (unique)
            );

        $this->articleRepository->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($article) {
                return $article instanceof Article && 
                       $article->slug()->toString() === 'test-article-2';
            }));

        $result = $this->useCase->execute($input);

        $this->assertTrue($result['success']);
    }

    public function test_throws_exception_after_max_attempts(): void
    {
        $input = [
            'title' => 'Test Article',
            'content' => 'Test content for article',
            'author_id' => '123e4567-e89b-12d3-a456-426614174000'
        ];

        // Mock repository to always return existing articles (simulate infinite duplicates)
        $this->articleRepository->expects($this->exactly(100))
            ->method('getBySlug')
            ->willReturn(new Article(ArticleId::generate(), UserId::generate(), Title::fromString('Existing'), Content::fromString('Content'), new Slug('test-article')));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to generate unique slug after 100 attempts');

        $this->useCase->execute($input);
    }

    public function test_validates_required_fields(): void
    {
        $input = [
            'title' => '',
 // Missing title
            'content' => 'Test content',
            'author_id' => '123e4567-e89b-12d3-a456-426614174000'
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Title is required');

        $this->useCase->execute($input);
    }

    public function test_validates_content_length(): void
    {
        $input = [
            'title' => 'Test Article',
            'content' => 'short', // Too short
            'author_id' => '123e4567-e89b-12d3-a456-426614174000'
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Content must be at least 10 characters long');

        $this->useCase->execute($input);
    }

    public function test_validates_title_length(): void
    {
        $input = [
            'title' => str_repeat('a', 256), // Too long
            'content' => 'Test content for article',
            'author_id' => '123e4567-e89b-12d3-a456-426614174000'
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Title must not exceed 255 characters');

        $this->useCase->execute($input);
    }

    public function test_validates_author_id(): void
    {
        $input = [
            'title' => 'Test Article',
            'content' => 'Test content for article',
            'author_id' => '' // Missing author ID
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Author ID is required');

        $this->useCase->execute($input);
    }
}
