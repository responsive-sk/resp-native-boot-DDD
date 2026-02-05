<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Mark;

use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Application\Blog\GetAllArticles;
use Blog\Infrastructure\View\ViewRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class DashboardController extends BaseController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private ViewRenderer $viewRenderer
    ) {
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(GetAllArticles::class);

        try {
            $result = $this->executeUseCase($request, $useCase, [], 'web');
            $articles = $result['articles'] ?? [];
        } catch (\Exception $e) {
            $articles = [];
        }

        return $this->viewRenderer->renderResponse('mark.dashboard', [
            'articles' => $articles,
            'articles_count' => count($articles),
        ]);
    }
}
