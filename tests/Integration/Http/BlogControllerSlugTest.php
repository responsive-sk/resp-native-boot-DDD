<?php

declare(strict_types=1);

namespace Tests\Integration\Http;

use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\Content;
use Blog\Domain\Blog\ValueObject\Title;
use Blog\Domain\User\ValueObject\UserId;
use Blog\Infrastructure\Http\Controller\Web\BlogController;
use Blog\Infrastructure\View\ViewRenderer;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

#[AllowMockObjectsWithoutExpectations]
final class BlogControllerSlugTest extends TestCase
{
    public function test_showBySlug_returns_200_and_contains_title(): void
    {
        $title = Title::fromString('Integration Article');
        $content = Content::fromString('Integration content');
        $authorId = UserId::fromString('00000000-0000-0000-0000-000000000001');

        $article = Article::create($title, $content, $authorId);

        $articleRepository = $this->createMock(ArticleRepository::class);
        $articleRepository->method('getBySlug')->willReturn($article);

        $viewRenderer = $this->createMock(ViewRenderer::class);
        $viewRenderer->method('renderResponse')->willReturn(new Response(200, ['Content-Type' => 'text/html'], 'Integration Article'));

        $controller = new \Blog\Infrastructure\Http\Controller\Web\BlogController($articleRepository, $viewRenderer);

        $request = (new ServerRequest('GET', '/blog/' . $article->slug()))->withAttribute('slug', (string) $article->slug());

        $response = $controller->showBySlug($request);

        $this->assertSame(200, $response->getStatusCode());
        $body = (string) $response->getBody();
        $this->assertStringContainsString('Integration Article', $body);
    }
}
