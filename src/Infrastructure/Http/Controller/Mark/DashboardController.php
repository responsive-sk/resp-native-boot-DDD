<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Mark;

use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Infrastructure\View\ViewRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class DashboardController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private ViewRenderer $viewRenderer
    ) {
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $articles = $this->articleRepository->getAll(); // alebo getLatest(5)

        return $this->viewRenderer->renderResponse('mark.dashboard', [
            'articles' => $articles,
            'articles_count' => count($articles),
        ]);
    }
}
