<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Api;

use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Application\Blog\CreateArticle;
use Blog\Application\Blog\DeleteArticle;
use Blog\Application\Blog\GetAllArticles;
use Blog\Application\Blog\UpdateArticle;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class ArticleApiController extends BaseController
{
    public function __construct(
        private \Blog\Domain\Blog\Repository\ArticleRepository $articleRepository
    ) {
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(GetAllArticles::class);
        
        try {
            $result = $this->executeUseCase($request, $useCase, [], 'api');
            
            // Transform articles to array format
            $articles = array_map(function ($article) {
                return $this->articleToArray($article);
            }, $result['articles'] ?? []);
            
            return $this->jsonResponse($articles);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $article = $this->articleRepository->getById($request->getAttribute('id'));

            if (!$article) {
                return $this->jsonResponse(['error' => 'Article not found'], 404);
            }

            return $this->jsonResponse($this->articleToArray($article));
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 404);
        }
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(CreateArticle::class);
        
        try {
            $result = $this->executeUseCase($request, $useCase, [
                'title' => 'body:title',
                'content' => 'body:content',
                'author_id' => 'session:user_id'
            ], 'api');

            $article = $this->articleRepository->getById($result['article_id']);

            if (!$article) {
                throw new \Exception('Failed to retrieve created article');
            }

            return $this->jsonResponse(
                [
                    'message' => 'Article created',
                    'article' => $this->articleToArray($article),
                ],
                201
            );
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
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
            ], 'api');

            return $this->jsonResponse(
                [
                    'message' => 'Article updated',
                    'article' => $this->articleToArray($result['article']),
                ]
            );
        } catch (\DomainException $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(DeleteArticle::class);
        
        try {
            $result = $this->executeUseCase($request, $useCase, [
                'article_id' => 'route:id'
            ], 'api');

            return $this->jsonResponse(
                ['message' => 'Article deleted successfully']
            );
        } catch (\DomainException $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Helper method to convert Article to array
     *
     * @return array<string, mixed>
     */
    private function articleToArray(\Blog\Domain\Blog\Entity\Article $article): array
    {
        $id = $article->id();
        $slug = $article->slug();

        return [
            'id' => $id ? $id->toInt() : null,
            'title' => $article->title()->toString(),
            'slug' => $slug ? $slug->toString() : null,
            'content' => $article->content()->toString(),
            'status' => $article->status()->toString(),
            'author_id' => $article->authorId()->toString(),  // UUID as string
            'created_at' => $article->createdAt()->format('Y-m-d H:i:s'),
            'updated_at' => $article->updatedAt()->format('Y-m-d H:i:s'),
            'uri' => $article->getUri(),
        ];
    }
}
