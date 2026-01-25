<?php

declare(strict_types=1);

namespace Tests\Integration\Http;

use App\Infrastructure\Http\Controller\BlogController;
use App\Application\Blog\Query\GetAllArticles\GetAllArticlesHandler;
use App\Application\Blog\Query\GetArticle\GetArticleHandler;
use App\Application\Blog\Query\GetArticleBySlug\GetArticleBySlugHandler;
use App\Domain\Blog\Repository\ArticleRepository;
use App\Domain\Blog\Entity\Article;
use App\Domain\Blog\ValueObject\Title;
use App\Domain\Blog\ValueObject\Content;
use App\Domain\User\ValueObject\UserId;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;

#[AllowMockObjectsWithoutExpectations]
final class BlogControllerSlugTest extends TestCase
{
    public function test_showBySlug_returns_200_and_contains_title(): void
    {
        $title = Title::fromString('Integration Article');
        $content = Content::fromString('Integration content');
        $authorId = UserId::fromInt(1);

        $article = Article::create($title, $content, $authorId);

        $articleRepository = $this->createMock(ArticleRepository::class);
        $articleRepository->method('findById')->willReturn($article);
        $articleRepository->method('findAll')->willReturn([$article]);
        $articleRepository->method('findBySlug')->willReturn($article);

        $getAll = new GetAllArticlesHandler($articleRepository);
        $getArticle = new GetArticleHandler($articleRepository);
        $getBySlug = new GetArticleBySlugHandler($articleRepository);

        $controller = new BlogController($getAll, $getArticle, $getBySlug);

        $request = (new ServerRequest('GET', '/blog/' . $article->slug()))->withAttribute('slug', (string) $article->slug());

        $response = $controller->showBySlug($request);

        $this->assertSame(200, $response->getStatusCode());
        $body = (string) $response->getBody();
        $this->assertStringContainsString('Integration Article', $body);
    }
}
