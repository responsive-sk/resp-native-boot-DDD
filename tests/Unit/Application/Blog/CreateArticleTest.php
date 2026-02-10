<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Blog;

use Blog\Application\Blog\CreateArticle;
use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\Content;
use Blog\Domain\Blog\ValueObject\Slug;
use Blog\Domain\Blog\ValueObject\Title;
use Blog\Domain\User\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

final class CreateArticleTest extends TestCase
{
    private CreateArticle $useCase;
    private $articleRepository;

    protected function setUp(): void
    {
        $this->articleRepository = $this->createMock(ArticleRepository::class);
        $this->useCase = new CreateArticle($this->articleRepository);
    }

    public function test_creates_article_with_unique_slug(): void
    {
        $input = [
            'title' => 'Test Article',
            'content' => 'Test content for article',
            'author_id' => '123e4567-e89b-12d3-a456-426614174000',
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
                return $article instanceof Article
                       && $article->getSlug()->toString() === 'test-article';
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
            'author_id' => '123e4567-e89b-12d3-a456-426614174000',
        ];

        // Track call count to return different values
        $callCount = 0;
        $this->articleRepository->expects($this->exactly(2))
            ->method('getBySlug')
            ->willReturnCallback(function ($slug) use (&$callCount) {
                $callCount++;
                $slugStr = $slug->toString();
                
                // First call with 'test-article' returns existing article
                if ($callCount === 1 && $slugStr === 'test-article') {
                    return new Article(
                        ArticleId::generate(),
                        UserId::generate(),
                        Title::fromString('Existing'),
                        Content::fromString('Content'),
                        new Slug('test-article')
                    );
                }
                
                // Second call with 'test-article-1' returns null (unique)
                if ($callCount === 2 && $slugStr === 'test-article-1') {
                    return null;
                }
                
                return null;
            });

        $this->articleRepository->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($article) {
                return $article instanceof Article
                       && $article->getSlug()->toString() === 'test-article-1';
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
            'author_id' => '123e4567-e89b-12d3-a456-426614174000',
        ];

        // Track call count to return different values
        $callCount = 0;
        $this->articleRepository->expects($this->exactly(3))
            ->method('getBySlug')
            ->willReturnCallback(function ($slug) use (&$callCount) {
                $callCount++;
                $slugStr = $slug->toString();
                
                // First two calls return existing articles
                if ($callCount === 1 && $slugStr === 'test-article') {
                    return new Article(
                        ArticleId::generate(),
                        UserId::generate(),
                        Title::fromString('Existing'),
                        Content::fromString('Content'),
                        new Slug('test-article')
                    );
                }
                
                if ($callCount === 2 && $slugStr === 'test-article-1') {
                    return new Article(
                        ArticleId::generate(),
                        UserId::generate(),
                        Title::fromString('Existing'),
                        Content::fromString('Content'),
                        new Slug('test-article-1')
                    );
                }
                
                // Third call returns null (unique)
                return null;
            });

        $this->articleRepository->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($article) {
                return $article instanceof Article
                       && $article->getSlug()->toString() === 'test-article-2';
            }));

        $result = $this->useCase->execute($input);

        $this->assertTrue($result['success']);
    }

    public function test_throws_exception_after_max_attempts(): void
    {
        $input = [
            'title' => 'Test Article',
            'content' => 'Test content for article',
            'author_id' => '123e4567-e89b-12d3-a456-426614174000',
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
            'author_id' => '123e4567-e89b-12d3-a456-426614174000',
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
            'author_id' => '123e4567-e89b-12d3-a456-426614174000',
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
            'author_id' => '123e4567-e89b-12d3-a456-426614174000',
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
            'author_id' => '', // Missing author ID
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Author ID is required');

        $this->useCase->execute($input);
    }
}
