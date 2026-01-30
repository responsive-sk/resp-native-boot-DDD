<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Blog\Command;

use App\Application\Blog\Command\CreateArticle\CreateArticleCommand;
use App\Application\Blog\Command\CreateArticle\CreateArticleHandler;
use App\Domain\Blog\Repository\ArticleRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineArticleRepository;
use PHPUnit\Framework\TestCase;

final class CreateArticleHandlerTest extends TestCase
{
    private ArticleRepository $articleRepository;
    private CreateArticleHandler $handler;

    protected function setUp(): void
    {
        $connection = \App\Database\DatabaseManager::getConnection('articles');
        $this->articleRepository = new DoctrineArticleRepository($connection);
        $this->handler = new CreateArticleHandler($this->articleRepository);
    }

    public function test_creates_article_successfully(): void
    {
        $articlesCountBefore = count($this->articleRepository->findAll());

        $command = new CreateArticleCommand(
            'Testovací článok',
            'Toto je obsah testovacieho článku.',
            1
        );

        $this->handler->handle($command);

        $articles = $this->articleRepository->findAll();
        $this->assertCount($articlesCountBefore + 1, $articles);

        // Nájdeme náš nový article
        $newArticle = null;
        foreach ($articles as $article) {
            if ($article->title()->toString() === 'Testovací článok') {
                $newArticle = $article;
                break;
            }
        }

        $this->assertNotNull($newArticle, 'Nový article nebol nájdený');
        $this->assertSame('Testovací článok', $newArticle->title()->toString());
        $this->assertSame('Toto je obsah testovacieho článku.', $newArticle->content()->toString());
        $this->assertSame(1, $newArticle->authorId()->toInt());
        $this->assertSame('draft', $newArticle->status()->toString());
    }

    public function test_throws_exception_for_invalid_title(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Titulok musí mať aspoň 3 znaky');

        $command = new CreateArticleCommand(
            'ab',
            'Toto je obsah testovacieho článku.',
            1
        );

        $this->handler->handle($command);
    }

    public function test_throws_exception_for_invalid_content(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Obsah musí mať aspoň 10 znakov');

        $command = new CreateArticleCommand(
            'Testovací článok',
            'Krátke',
            1
        );

        $this->handler->handle($command);
    }
}
