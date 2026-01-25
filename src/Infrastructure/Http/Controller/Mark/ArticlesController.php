<?php
declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Mark;

use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Infrastructure\View\ViewRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class ArticlesController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private ViewRenderer $viewRenderer
    ) {}

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $articles = $this->articleRepository->getAll();

        return $this->viewRenderer->renderResponse('mark.articles.index', [
            'articles' => $articles,
        ]);
    }

    public function show(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $article = $this->articleRepository->getById($id);

        if (!$article) {
            // TODO: Return proper error response
            return $this->viewRenderer->renderResponse('error.404', [], 404);
        }

        return $this->viewRenderer->renderResponse('mark.articles.show', [
            'article' => $article,
        ]);
    }
}