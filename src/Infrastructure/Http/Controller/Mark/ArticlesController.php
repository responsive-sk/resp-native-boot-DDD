<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Mark;

use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Application\Blog\CreateArticle;
use Blog\Application\Blog\UpdateArticle;
use Blog\Application\Blog\DeleteArticle;
use Blog\Application\Blog\GetAllArticles;
use Blog\Infrastructure\View\ViewRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ArticlesController extends BaseController
{
    public function __construct(
        \Psr\Container\ContainerInterface $container,
        \Blog\Core\UseCaseHandler $useCaseHandler,
        private ArticleRepository $articleRepository,
        private ViewRenderer $viewRenderer
    ) {
        parent::__construct($container, $useCaseHandler);
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

        return $this->viewRenderer->renderResponse('mark.articles.index', [
            'articles' => $articles,
        ]);
    }

    public function show(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $article = $this->articleRepository->getById(ArticleId::fromInt($id));

        if (!$article) {
            return $this->viewRenderer->renderResponse('error.404', [], 404);
        }

        return $this->viewRenderer->renderResponse('mark.articles.show', [
            'article' => $article,
        ]);
    }

    public function createForm(ServerRequestInterface $request): ResponseInterface
    {
        return $this->viewRenderer->renderResponse('mark.articles.create', []);
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        // ✅ SECURITY: Require MARK role for admin operations
        $user = $this->requireMarkWeb();
        if ($user === null) {
            return $this->htmlResponse('Access denied: MARK role required', 403);
        }

        $useCase = $this->useCaseHandler->get(CreateArticle::class);

        try {
            $result = $this->executeUseCase($request, $useCase, [
                'title' => 'body:title',
                'content' => 'body:content',
                'author_id' => 'session:user_id'
            ], 'web');

            return $this->redirect('/mark/articles');
        } catch (\Exception $e) {
            return $this->viewRenderer->renderResponse('mark.articles.create', [
                'error' => $e->getMessage(),
                'title' => $request->getParsedBody()['title'] ?? '',
                'content' => $request->getParsedBody()['content'] ?? '',
            ]);
        }
    }

    public function editForm(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $article = $this->articleRepository->getById(ArticleId::fromInt($id));

        if (!$article) {
            return $this->viewRenderer->renderResponse('error.404', [], 404);
        }

        return $this->viewRenderer->renderResponse('mark.articles.edit', [
            'article' => $article,
        ]);
    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        // ✅ SECURITY: Require MARK role and ownership
        $id = (int) $request->getAttribute('id');
        $user = $this->requireArticleOwnershipWeb($id);
        if ($user === null) {
            return $this->htmlResponse('Access denied: You can only modify your own articles', 403);
        }

        $useCase = $this->useCaseHandler->get(UpdateArticle::class);

        try {
            $result = $this->executeUseCase($request, $useCase, [
                'article_id' => 'route:id',
                'title' => 'body:title',
                'content' => 'body:content',
                'slug' => 'body:slug'
            ], 'web');

            return $this->redirect('/mark/articles');
        } catch (\Exception $e) {
            return $this->viewRenderer->renderResponse('mark.articles.edit', [
                'error' => $e->getMessage(),
                'article' => $result['article'] ?? null,
            ]);
        }
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        // ✅ SECURITY: Require MARK role and ownership
        $id = (int) $request->getAttribute('id');
        $user = $this->requireArticleOwnershipWeb($id);
        if ($user === null) {
            return $this->htmlResponse('Access denied: You can only delete your own articles', 403);
        }

        $useCase = $this->useCaseHandler->get(DeleteArticle::class);

        try {
            $result = $this->executeUseCase($request, $useCase, [
                'article_id' => 'route:id'
            ], 'web');

            return $this->redirect('/mark/articles');
        } catch (\Exception $e) {
            return $this->htmlResponse('Error deleting article: ' . $e->getMessage(), 400);
        }
    }
}
