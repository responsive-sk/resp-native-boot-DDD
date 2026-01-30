<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Blog\Query;

use Blog\Application\Blog\GetAllArticles;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineArticleRepository;
use PHPUnit\Framework\TestCase;

final class GetAllArticlesHandlerTest extends TestCase
{
    private ArticleRepository $articleRepository;
    private GetAllArticles $handler;

    protected function setUp(): void
    {
        $connection = \Blog\Database\DatabaseManager::getConnection('articles');
        $this->articleRepository = new DoctrineArticleRepository($connection);
        $this->handler = new GetAllArticles($this->articleRepository);
    }

    public function test_returns_all_articles(): void
    {
        $articles = ($this->handler)();

        $this->assertNotEmpty($articles);

        foreach ($articles as $article) {
            $this->assertInstanceOf(\Blog\Domain\Blog\Entity\Article::class, $article);
        }
    }
}
