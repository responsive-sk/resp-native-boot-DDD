<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Blog\Query;

use Blog\Application\Blog\Query\GetAllArticles\GetAllArticlesQuery;
use Blog\Application\Blog\Query\GetAllArticles\GetAllArticlesHandler;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineArticleRepository;
use Blog\Database\Database;
use PHPUnit\Framework\TestCase;

final class GetAllArticlesHandlerTest extends TestCase
{
    private ArticleRepository $articleRepository;
    private GetAllArticlesHandler $handler;

    protected function setUp(): void
    {
        $connection = \App\Database\DatabaseManager::getConnection('articles');
        $this->articleRepository = new DoctrineArticleRepository($connection);
        $this->handler = new GetAllArticlesHandler($this->articleRepository);
    }

    public function test_returns_all_articles(): void
    {
        $query = new GetAllArticlesQuery();

        $articles = $this->handler->handle($query);

        $this->assertIsArray($articles);
        $this->assertNotEmpty($articles);

        foreach ($articles as $article) {
            $this->assertInstanceOf(\Blog\Domain\Blog\Entity\Article::class, $article);
        }
    }
}

