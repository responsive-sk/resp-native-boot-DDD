<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Web;

use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Application\Blog\CreateArticle;
use Blog\Application\Blog\UpdateArticle;
use Blog\Application\Blog\DeleteArticle;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Infrastructure\View\ViewRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class ArticleController extends BaseController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private ViewRenderer $viewRenderer
    ) {
    }

    public function createForm(ServerRequestInterface $request): ResponseInterface
    {
        return $this->viewRenderer->renderResponse('article.create');
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(CreateArticle::class);

        try {
            $result = $this->executeUseCase($request, $useCase, [
                'title' => 'body:title',
                'content' => 'body:content',
                'author_id' => 'session:user_id'
            ], 'web');

            // Redirect to the new article
            return $this->redirect('/blog/' . $result['article_id']);

        } catch (\Exception $e) {
            return $this->viewRenderer->renderResponse('article.create', [
                'error' => $e->getMessage(),
                'title' => $request->getParsedBody()['title'] ?? '',
                'content' => $request->getParsedBody()['content'] ?? '',
            ], 400);
        }
    }

    public function editForm(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $article = $this->articleRepository->getById(\Blog\Domain\Blog\ValueObject\ArticleId::fromInt($id));

        if (!$article) {
            return $this->htmlResponse('Article not found', 404);
        }

        return $this->viewRenderer->renderResponse('article.edit', [
            'article' => $article,
        ]);
    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(UpdateArticle::class);
        
        try {
            $result = $this->executeUseCase($request, $useCase, [
                'article_id' => 'route:id',
                'title' => 'body:title',
                'content' => 'body:content',
                'slug' => 'body:slug'
            ], 'web');

            return $this->redirect('/blog/' . $result['article_id']);
        } catch (\Exception $e) {
            return $this->viewRenderer->renderResponse('article.edit', [
                'error' => $e->getMessage(),
                'article' => $result['article'] ?? null,
            ], 400);
        }
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(DeleteArticle::class);
        
        try {
            $result = $this->executeUseCase($request, $useCase, [
                'article_id' => 'route:id'
            ], 'web');

            return $this->redirect('/blog');
        } catch (\Exception $e) {
            return $this->htmlResponse('Error deleting article: ' . $e->getMessage(), 400);
        }
    }
}
