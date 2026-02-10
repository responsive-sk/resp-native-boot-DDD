<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastructure\Persistence;

use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\AuthorId;
use Blog\Domain\Blog\ValueObject\Category;
use Blog\Domain\Blog\ValueObject\CategoryId;
use Blog\Domain\Blog\ValueObject\Content;
use Blog\Domain\Blog\ValueObject\Slug;
use Blog\Domain\Blog\ValueObject\Title;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineArticleRepository;
use Doctrine\DBAL\DriverManager;
use PHPUnit\Framework\TestCase;

/**
 * @group integration
 * @group database
 */
final class DoctrineArticleRepositoryTest extends TestCase
{
    private static ?\Doctrine\DBAL\Connection $connection = null;
    private ArticleRepository $repository;

    public static function setUpBeforeClass(): void
    {
        // Create in-memory SQLite database for testing
        self::$connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ]);

        // Create schema
        self::createSchema();
    }

    private static function createSchema(): void
    {
        $sql = <<<SQL
CREATE TABLE authors (
    id TEXT PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    email TEXT UNIQUE NOT NULL,
    role TEXT NOT NULL DEFAULT 'author',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE articles (
    id TEXT PRIMARY KEY,
    title TEXT NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    excerpt TEXT,
    content TEXT NOT NULL,
    author_id TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'draft',
    featured_image_url TEXT,
    meta_title TEXT,
    meta_description TEXT,
    published_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    category_id TEXT,
    FOREIGN KEY (author_id) REFERENCES authors(id)
);

CREATE TABLE categories (
    id TEXT PRIMARY KEY,
    name TEXT UNIQUE NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE tags (
    id TEXT PRIMARY KEY,
    name TEXT UNIQUE NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE article_tags (
    article_id TEXT NOT NULL,
    tag_id TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

CREATE INDEX idx_articles_slug ON articles(slug);
CREATE INDEX idx_articles_status ON articles(status);
CREATE INDEX idx_articles_author_id ON articles(author_id);

INSERT INTO authors (id, username, email, role) VALUES 
    ('author-1', 'testauthor', 'test@example.com', 'author');
SQL;

        self::$connection->executeStatement($sql);
    }

    protected function setUp(): void
    {
        $this->repository = new DoctrineArticleRepository(self::$connection);
        $this->cleanupDatabase();
    }

    private function cleanupDatabase(): void
    {
        self::$connection->executeStatement("DELETE FROM article_tags");
        self::$connection->executeStatement("DELETE FROM articles");
        self::$connection->executeStatement("DELETE FROM tags");
        self::$connection->executeStatement("DELETE FROM categories");
    }

    public function test_can_save_and_retrieve_article(): void
    {
        $article = $this->createTestArticle('Test Article', 'test-article');

        $this->repository->add($article);

        $retrieved = $this->repository->getById($article->id());

        $this->assertNotNull($retrieved);
        $this->assertEquals($article->id()->toString(), $retrieved->id()->toString());
        $this->assertEquals('Test Article', $retrieved->title()->toString());
        $this->assertEquals('test-article', $retrieved->slug()->toString());
    }

    public function test_can_find_article_by_slug(): void
    {
        $article = $this->createTestArticle('Test Article', 'test-article');
        $this->repository->add($article);

        $retrieved = $this->repository->getBySlug(new Slug('test-article'));

        $this->assertNotNull($retrieved);
        $this->assertEquals($article->id()->toString(), $retrieved->id()->toString());
    }

    public function test_returns_null_for_nonexistent_slug(): void
    {
        $retrieved = $this->repository->getBySlug(new Slug('nonexistent-article'));

        $this->assertNull($retrieved);
    }

    public function test_can_update_existing_article(): void
    {
        $article = $this->createTestArticle('Original Title', 'original-title');
        $this->repository->add($article);

        // Update the article
        $article->updateTitle(Title::fromString('Updated Title'));
        $this->repository->update($article);

        $retrieved = $this->repository->getById($article->id());

        $this->assertEquals('Updated Title', $retrieved->title()->toString());
    }

    public function test_can_remove_article(): void
    {
        $article = $this->createTestArticle('Test Article', 'test-article');
        $this->repository->add($article);

        $this->repository->remove($article->id());

        $retrieved = $this->repository->getById($article->id());
        $this->assertNull($retrieved);
    }

    public function test_get_all_returns_all_articles(): void
    {
        $article1 = $this->createTestArticle('Article 1', 'article-1');
        $article2 = $this->createTestArticle('Article 2', 'article-2');

        $this->repository->add($article1);
        $this->repository->add($article2);

        $all = $this->repository->getAll();

        $this->assertCount(2, $all);
    }

    public function test_find_published_returns_only_published(): void
    {
        $published = $this->createTestArticle('Published', 'published-article');
        $published->publish();

        $draft = $this->createTestArticle('Draft', 'draft-article');

        $this->repository->add($published);
        $this->repository->add($draft);

        $publishedArticles = $this->repository->findPublished();

        $this->assertCount(1, $publishedArticles);
        $this->assertEquals('Published', $publishedArticles[0]->title()->toString());
    }

    public function test_find_by_status_returns_filtered_articles(): void
    {
        $published = $this->createTestArticle('Published', 'published-article');
        $published->publish();

        $draft = $this->createTestArticle('Draft', 'draft-article');

        $this->repository->add($published);
        $this->repository->add($draft);

        $draftArticles = $this->repository->findByStatus('draft');

        $this->assertCount(1, $draftArticles);
        $this->assertEquals('Draft', $draftArticles[0]->title()->toString());
    }

    public function test_count_returns_correct_number(): void
    {
        $article1 = $this->createTestArticle('Article 1', 'article-1');
        $article2 = $this->createTestArticle('Article 2', 'article-2');

        $this->repository->add($article1);
        $this->repository->add($article2);

        $count = $this->repository->count();

        $this->assertEquals(2, $count);
    }

    public function test_count_with_status_filter(): void
    {
        $published = $this->createTestArticle('Published', 'published-article');
        $published->publish();

        $draft = $this->createTestArticle('Draft', 'draft-article');

        $this->repository->add($published);
        $this->repository->add($draft);

        $count = $this->repository->count(['status' => 'published']);

        $this->assertEquals(1, $count);
    }

    public function test_handles_special_characters_in_content(): void
    {
        $content = 'Content with special chars: <script>alert("xss")</script> and "quotes"';
        $article = $this->createTestArticle(
            'Special Chars',
            'special-chars',
            $content
        );

        $this->repository->add($article);

        $retrieved = $this->repository->getById($article->id());

        $this->assertEquals($content, $retrieved->content()->toString());
    }

    public function test_handles_unicode_characters(): void
    {
        $article = $this->createTestArticle(
            'Článek s háčky a čárkami',
            'clanek-s-hacky-a-carkami',
            'Obsah s Unicode znaky: 🎉 🚀 ñ 中文'
        );

        $this->repository->add($article);

        $retrieved = $this->repository->getById($article->id());

        $this->assertEquals('Článek s háčky a čárkami', $retrieved->title()->toString());
        $this->assertStringContainsString('🎉', $retrieved->content()->toString());
    }

    public function test_handles_long_content(): void
    {
        $longContent = str_repeat('Lorem ipsum dolor sit amet. ', 1000); // ~28k characters

        $article = $this->createTestArticle(
            'Long Content Article',
            'long-content-article',
            $longContent
        );

        $this->repository->add($article);

        $retrieved = $this->repository->getById($article->id());

        $this->assertEquals($longContent, $retrieved->content()->toString());
    }

    private function createTestArticle(
        string $title,
        string $slug,
        string $content = 'Test content'
    ): Article {
        return Article::create(
            Title::fromString($title),
            new Content($content),
            AuthorId::fromString('author-1')
        );
    }

    protected function tearDown(): void
    {
        $this->cleanupDatabase();
    }
}
