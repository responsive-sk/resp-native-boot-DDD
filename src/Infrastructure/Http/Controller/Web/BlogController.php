<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Web;

use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\Slug;
use Blog\Infrastructure\View\ViewRenderer;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class BlogController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private ViewRenderer $viewRenderer
    ) {
    }

    public function home(ServerRequestInterface $request): ResponseInterface
    {
        return $this->viewRenderer->renderResponse('home');
    }

    public function about(ServerRequestInterface $request): ResponseInterface
    {
        return $this->viewRenderer->renderResponse('about');
    }

    public function contact(ServerRequestInterface $request): ResponseInterface
    {
        return $this->viewRenderer->renderResponse('contact');
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $articles = $this->articleRepository->getAll();
        $page = (int) ($request->getQueryParams()['page'] ?? 1);

        return $this->viewRenderer->renderResponse('blog.index', [
            'articles' => $articles,
            'page' => $page,
        ]);
    }

    public function show(ServerRequestInterface $request): ResponseInterface
    {
        $idRaw = $request->getAttribute('id');

        // Basic validation + conversion to value object
        if (!is_scalar($idRaw) || !ctype_digit((string) $idRaw) || (int) $idRaw <= 0) {
            return new Response(404, ['Content-Type' => 'text/html'], 'Invalid or missing article ID');
        }

        $id = ArticleId::fromInt((int) $idRaw);

        $article = $this->articleRepository->getById($id);

        if ($article === null) {
            return new Response(404, ['Content-Type' => 'text/html'], 'Article not found');
        }

        return $this->viewRenderer->renderResponse('blog.show', [
            'article' => $article,
        ]);
    }

    public function showBySlug(ServerRequestInterface $request): ResponseInterface
    {
        $slugString = $request->getAttribute('slug');

        if (!is_string($slugString) || trim($slugString) === '') {
            return new Response(404, ['Content-Type' => 'text/html'], 'Invalid or missing slug');
        }

        $slug = Slug::fromString($slugString);

        $article = $this->articleRepository->getBySlug($slug);

        if ($article === null) {
            return new Response(404, ['Content-Type' => 'text/html'], 'Article not found');
        }

        return $this->viewRenderer->renderResponse('blog.show', [
            'article' => $article,
        ]);
    }
}
