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
        \Psr\Container\ContainerInterface $container,
        \Blog\Core\UseCaseHandler $useCaseHandler,
        private ArticleRepository $articleRepository,
        private ViewRenderer $viewRenderer,
        \Blog\Security\AuthorizationService $authorization
    ) {
        parent::__construct($container, $useCaseHandler, $authorization);
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        // ✅ SECURITY: Require MARK role for admin operations
        $user = $this->requireMarkWeb();
        if ($user === null) {
            return $this->htmlResponse('Access denied: MARK role required', 403);
        }

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
