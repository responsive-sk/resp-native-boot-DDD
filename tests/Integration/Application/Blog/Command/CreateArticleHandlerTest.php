<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Blog\Command;

use Blog\Application\Blog\CreateArticle;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineArticleRepository;
use PHPUnit\Framework\TestCase;

final class CreateArticleHandlerTest extends TestCase
{
    private ArticleRepository $articleRepository;
    private CreateArticle $handler;

    protected function setUp(): void
    {
        $connection = \Blog\Database\DatabaseManager::getConnection('articles');
        $this->articleRepository = new DoctrineArticleRepository($connection);
        $this->handler = new CreateArticle($this->articleRepository);
    }

    public function test_creates_article_successfully(): void
    {
        $title = 'Testovací článok';
        $content = 'Toto je obsah testovacieho článku.';
        $authorId = '00000000-0000-0000-0000-000000000001';

        $articleId = ($this->handler)(
            $title,
            $content,
            $authorId
        );

        $this->assertInstanceOf(\Blog\Domain\Blog\ValueObject\ArticleId::class, $articleId);

        $newArticle = $this->articleRepository->getById($articleId);

        $this->assertNotNull($newArticle, 'Nový article nebol nájdený');
        $this->assertSame('Testovací článok', $newArticle->title()->toString());
        $this->assertSame('Toto je obsah testovacieho článku.', $newArticle->content()->toString());
        $this->assertSame($authorId, $newArticle->authorId()->toString());
        $this->assertSame('draft', $newArticle->status()->toString());
    }

    public function test_throws_exception_for_invalid_title(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Titulok musí mať aspoň 3 znaky');

        $title = 'ab';
        $content = 'Toto je obsah testovacieho článku.';
        $authorId = '00000000-0000-0000-0000-000000000001';

        ($this->handler)(
            $title,
            $content,
            $authorId
        );
    }

    public function test_throws_exception_for_invalid_content(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Obsah musí mať aspoň 10 znakov');

        $title = 'Testovací článok';
        $content = 'Krátke';
        $authorId = '00000000-0000-0000-0000-000000000001';

        ($this->handler)(
            $title,
            $content,
            $authorId
        );
    }
}
