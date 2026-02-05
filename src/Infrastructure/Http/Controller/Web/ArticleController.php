<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Web;

use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Infrastructure\View\ViewRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ArticleController extends BaseController
{
    public function __construct(
        private ViewRenderer $renderer
    ) {
        // BaseController constructor will be called by DI container
    }
    
    public function show(ServerRequestInterface $request, string $slug): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\GetArticleBySlug::class);
        
        $result = $this->executeUseCase($request, $useCase, [
            'slug' => 'route:slug'
        ], 'web');
        
        $html = $this->renderer->renderResponse('article.show', [
            'article' => $result['article'] ?? null
        ]);
        
        return $html;
    }
    
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\GetAllArticles::class);
        
        $result = $this->executeUseCase($request, $useCase, [
            'page' => 'query:page',
            'category' => 'query:category',
            'tag' => 'query:tag'
        ], 'web');
        
        $html = $this->renderer->renderResponse('article.index', [
            'articles' => $result['articles'] ?? [],
            'count' => $result['count'] ?? 0,
            'currentPage' => $request->getQueryParams()['page'] ?? 1
        ]);
        
        return $html;
    }
    
    public function createForm(ServerRequestInterface $request): ResponseInterface
    {
        return $this->renderer->renderResponse('article.create', []);
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\CreateArticle::class);

        try {
            $result = $this->executeUseCase($request, $useCase, [
                'title' => 'body:title',
                'content' => 'body:content',
                'author_id' => 'session:user_id'
            ], 'web');

            return $this->redirect('/blog/' . $result['article_id']);

        } catch (\Exception $e) {
            return $this->renderer->renderResponse('article.create', [
                'error' => $e->getMessage(),
                'title' => $request->getParsedBody()['title'] ?? '',
                'content' => $request->getParsedBody()['content'] ?? '',
            ]);
        }
    }

    public function editForm(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $article = $this->get('article_repository')->getById(\Blog\Domain\Blog\ValueObject\ArticleId::fromInt($id));

        if (!$article) {
            return $this->htmlResponse('Article not found', 404);
        }

        return $this->renderer->renderResponse('article.edit', [
            'article' => $article
        ]);
    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\UpdateArticle::class);

        try {
            $result = $this->executeUseCase($request, $useCase, [
                'article_id' => 'route:id',
                'title' => 'body:title',
                'content' => 'body:content',
                'slug' => 'body:slug'
            ], 'web');

            return $this->redirect('/blog/' . $result['article_id']);
        } catch (\Exception $e) {
            return $this->renderer->renderResponse('article.edit', [
                'error' => $e->getMessage(),
                'article' => $result['article'] ?? null,
            ], 400);
        }
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\DeleteArticle::class);
        
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
