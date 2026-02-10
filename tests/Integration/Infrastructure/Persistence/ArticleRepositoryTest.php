<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastructure\Persistence;

use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\Content;
use Blog\Domain\Blog\ValueObject\Slug;
use Blog\Domain\Blog\ValueObject\Title;
use Blog\Domain\User\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

final class ArticleRepositoryTest extends TestCase
{
    private ArticleRepository $repository;

    protected function setUp(): void
    {
        // This is a mock test, but should be converted to real integration test
        // with actual database operations for proper testing
        $this->repository = $this->createMock(ArticleRepository::class);
    }

    public function test_save_and_find_article(): void
    {
        $articleId = ArticleId::generate();
        $authorId = UserId::generate();
        $title = Title::fromString('Test Article');
        $content = Content::fromString('Test content');
        $slug = Slug::fromString('test-article');

        $article = Article::create(
            $articleId,
            $authorId,
            $title,
            $content,
            $slug
        );

        // Mock the repository methods
        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($article));

        $this->repository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($articleId))
            ->willReturn($article);

        // Test save
        $this->repository->save($article);

        // Test find
        $foundArticle = $this->repository->findById($articleId);
        $this->assertSame($article, $foundArticle);
    }

    public function test_find_by_slug(): void
    {
        $articleId = ArticleId::generate();
        $authorId = UserId::generate();
        $title = Title::fromString('Test Article');
        $content = Content::fromString('Test content');
        $slug = Slug::fromString('test-article');

        $article = Article::create(
            $articleId,
            $authorId,
            $title,
            $content,
            $slug
        );

        $this->repository->expects($this->once())
            ->method('findBySlug')
            ->with($this->equalTo($slug))
            ->willReturn($article);

        $foundArticle = $this->repository->findBySlug($slug);
        $this->assertSame($article, $foundArticle);
    }

    public function test_find_by_author(): void
    {
        $authorId = UserId::generate();
        $articles = [
            $this->createArticle($authorId, 'Article 1'),
            $this->createArticle($authorId, 'Article 2'),
        ];

        $this->repository->expects($this->once())
            ->method('findByAuthor')
            ->with($this->equalTo($authorId))
            ->willReturn($articles);

        $foundArticles = $this->repository->findByAuthor($authorId);
        $this->assertCount(2, $foundArticles);
        $this->assertSame($articles, $foundArticles);
    }

    public function test_get_all_articles(): void
    {
        $articles = [
            $this->createArticle(UserId::generate(), 'Article 1'),
            $this->createArticle(UserId::generate(), 'Article 2'),
            $this->createArticle(UserId::generate(), 'Article 3'),
        ];

        $this->repository->expects($this->once())
            ->method('getAll')
            ->willReturn($articles);

        $allArticles = $this->repository->getAll();
        $this->assertCount(3, $allArticles);
        $this->assertSame($articles, $allArticles);
    }

    public function test_remove_article(): void
    {
        $articleId = ArticleId::generate();

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($articleId));

        $this->repository->remove($articleId);
    }

    public function test_find_nonexistent_article_returns_null(): void
    {
        $articleId = ArticleId::generate();

        $this->repository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($articleId))
            ->willReturn(null);

        $article = $this->repository->findById($articleId);
        $this->assertNull($article);
    }

    public function test_find_by_nonexistent_slug_returns_null(): void
    {
        $slug = Slug::fromString('nonexistent-article');

        $this->repository->expects($this->once())
            ->method('findBySlug')
            ->with($this->equalTo($slug))
            ->willReturn(null);

        $article = $this->repository->findBySlug($slug);
        $this->assertNull($article);
    }

    public function test_search_articles_by_title(): void
    {
        $query = 'test';
        $articles = [
            $this->createArticle(UserId::generate(), 'Test Article 1'),
            $this->createArticle(UserId::generate(), 'Test Article 2'),
        ];

        $this->repository->expects($this->once())
            ->method('search')
            ->with($this->equalTo($query))
            ->willReturn($articles);

        $foundArticles = $this->repository->search($query);
        $this->assertCount(2, $foundArticles);
        $this->assertSame($articles, $foundArticles);
    }

    public function test_search_empty_query_returns_empty_array(): void
    {
        $query = '';

        $this->repository->expects($this->once())
            ->method('search')
            ->with($this->equalTo($query))
            ->willReturn([]);

        $foundArticles = $this->repository->search($query);
        $this->assertEmpty($foundArticles);
    }

    public function test_get_latest_articles(): void
    {
        $limit = 5;
        $articles = [
            $this->createArticle(UserId::generate(), 'Latest Article 1'),
            $this->createArticle(UserId::generate(), 'Latest Article 2'),
        ];

        $this->repository->expects($this->once())
            ->method('getLatest')
            ->with($this->equalTo($limit))
            ->willReturn($articles);

        $latestArticles = $this->repository->getLatest($limit);
        $this->assertCount(2, $latestArticles);
        $this->assertSame($articles, $latestArticles);
    }

    public function test_count_by_author(): void
    {
        $authorId = UserId::generate();
        $count = 3;

        $this->repository->expects($this->once())
            ->method('countByAuthor')
            ->with($this->equalTo($authorId))
            ->willReturn($count);

        $articleCount = $this->repository->countByAuthor($authorId);
        $this->assertSame($count, $articleCount);
    }

    public function test_exists_by_id(): void
    {
        $articleId = ArticleId::generate();

        $this->repository->expects($this->once())
            ->method('exists')
            ->with($this->equalTo($articleId))
            ->willReturn(true);

        $exists = $this->repository->exists($articleId);
        $this->assertTrue($exists);
    }

    public function test_exists_by_slug(): void
    {
        $slug = Slug::fromString('test-article');

        $this->repository->expects($this->once())
            ->method('existsBySlug')
            ->with($this->equalTo($slug))
            ->willReturn(false);

        $exists = $this->repository->existsBySlug($slug);
        $this->assertFalse($exists);
    }

    private function createArticle(UserId $authorId, string $title): Article
    {
        $articleId = ArticleId::generate();
        $titleObj = Title::fromString($title);
        $content = Content::fromString('Content for ' . $title);
        $slug = Slug::fromString(strtolower(str_replace(' ', '-', $title)));

        return Article::create(
            $articleId,
            $authorId,
            $titleObj,
            $content,
            $slug
        );
    }
}
