<?php
declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Api;

use Blog\Infrastructure\Http\Controller\BaseController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ArticleApiController extends BaseController
{
    public function getAll(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\GetAllArticles::class);
        
        $data = $this->executeUseCase($request, $useCase, [
            'page' => 'query:page',
            'limit' => 'query:limit',
            'category' => 'query:category',
            'tag' => 'query:tag'
        ], 'api');
        
        return $this->jsonResponse([
            'success' => true,
            'data' => $data,
            'meta' => [
                'count' => count($data['articles'] ?? []),
                'timestamp' => time()
            ]
        ]);
    }
    
    public function getBySlug(ServerRequestInterface $request, string $slug): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\GetArticleBySlug::class);
        
        $data = $this->executeUseCase($request, $useCase, [
            'slug' => 'route:slug'
        ], 'api');
        
        return $this->jsonResponse([
            'success' => true,
            'data' => $data
        ]);
    }
    
    public function getById(ServerRequestInterface $request, int $id): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\GetArticleById::class);
        
        $data = $this->executeUseCase($request, $useCase, [
            'article_id' => $id
        ], 'api');
        
        return $this->jsonResponse([
            'success' => true,
            'data' => $data
        ]);
    }
    
    public function create(ServerRequestInterface $request): ResponseInterface
    {
        // SECURITY: Require authentication
        $user = $this->requireAuth();
        if ($user === null) {
            return $this->jsonResponse([
                'success' => false,
                'error' => 'Authentication required',
                'message' => 'You must be logged in to create articles'
            ], 401);
        }

        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\CreateArticle::class);
        
        $data = $this->executeUseCase($request, $useCase, [
            'title' => 'body:title',
            'content' => 'body:content',
            'category_id' => 'body:category_id',
            'tags' => 'body:tags',
            'status' => 'body:status',
            'author_id' => 'session:user_id'
        ], 'api');
        
        return $this->jsonResponse([
            'success' => true,
            'data' => $data,
            'message' => 'Article created successfully'
        ], 201);
    }
    
    public function update(ServerRequestInterface $request, int $id): ResponseInterface
    {
        // SECURITY: Require authentication and ownership
        $user = $this->requireArticleOwnership($id);
        if ($user === null) {
            return $this->jsonResponse([
                'success' => false,
                'error' => 'Access denied',
                'message' => 'You can only modify your own articles'
            ], 403);
        }

        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\UpdateArticle::class);
        
        $data = $this->executeUseCase($request, $useCase, [
            'article_id' => $id,
            'title' => 'body:title',
            'content' => 'body:content',
            'slug' => 'body:slug',
            'category_id' => 'body:category_id',
            'tags' => 'body:tags',
            'status' => 'body:status'
        ], 'api');
        
        return $this->jsonResponse([
            'success' => true,
            'data' => $data,
            'message' => 'Article updated successfully'
        ]);
    }
    
    public function delete(ServerRequestInterface $request, int $id): ResponseInterface
    {
        // SECURITY: Require authentication and ownership
        $user = $this->requireArticleOwnership($id);
        if ($user === null) {
            return $this->jsonResponse([
                'success' => false,
                'error' => 'Access denied',
                'message' => 'You can only delete your own articles'
            ], 403);
        }

        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\DeleteArticle::class);
        
        $data = $this->executeUseCase($request, $useCase, [
            'article_id' => $id
        ], 'api');
        
        return $this->jsonResponse([
            'success' => true,
            'data' => $data,
            'message' => 'Article deleted successfully'
        ]);
    }
    
    public function search(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\SearchArticles::class);
        
        $data = $this->executeUseCase($request, $useCase, [
            'query' => 'query:q',
            'page' => 'query:page',
            'limit' => 'query:limit'
        ], 'api');
        
        return $this->jsonResponse([
            'success' => true,
            'data' => $data,
            'meta' => [
                'count' => count($data['articles'] ?? []),
                'query' => $request->getQueryParams()['q'] ?? ''
            ]
        ]);
    }
}
