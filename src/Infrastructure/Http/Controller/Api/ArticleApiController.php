<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Api;

use Blog\Application\Blog\GetAllArticles;
use Blog\Application\Blog\CreateArticle;
use Blog\Application\Blog\UpdateArticle;
use Blog\Application\Blog\DeleteArticle;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\Title;
use Blog\Domain\Blog\ValueObject\Content;
use Blog\Domain\Blog\ValueObject\Slug;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;

final readonly class ArticleApiController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private GetAllArticles $getAllArticles,
        private CreateArticle $createArticle,
        private UpdateArticle $updateArticle,
        private DeleteArticle $deleteArticle
    ) {}

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $articles = $this->getAllArticles->__invoke();
        
        $articleData = array_map(function ($article) {
            return $this->articleToArray($article);
        }, $articles);
        
        return $this->jsonResponse($articleData);
    }

    public function show(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');

        try {
            $articleId = ArticleId::fromInt($id);
            $article = $this->articleRepository->getById($articleId);
            
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
        $data = json_decode((string) $request->getBody(), true);

        try {
            $title = Title::fromString($data['title'] ?? '');
            $content = Content::fromString($data['content'] ?? '');
            $authorId = (int) ($data['authorId'] ?? 1);

            $articleId = $this->createArticle->__invoke(
                $title->toString(), 
                $content->toString(), 
                $authorId
            );

            $article = $this->articleRepository->getById($articleId);

            return $this->jsonResponse(
                [
                    'message' => 'Article created', 
                    'article' => $this->articleToArray($article)
                ], 
                201
            );
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $data = json_decode((string) $request->getBody(), true);

        try {
            $articleId = ArticleId::fromInt($id);
            $title = Title::fromString($data['title'] ?? '');
            $content = Content::fromString($data['content'] ?? '');
            
            // Optional slug
            $slug = isset($data['slug']) ? new Slug($data['slug']) : null;

            $article = $this->updateArticle->__invoke($articleId, $title, $content, $slug);

            return $this->jsonResponse(
                [
                    'message' => 'Article updated',
                    'article' => $this->articleToArray($article)
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
        $id = (int) $request->getAttribute('id');

        try {
            $articleId = ArticleId::fromInt($id);
            $this->deleteArticle->__invoke($articleId);

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
     */
    private function articleToArray($article): array
    {
        $id = $article->id();
        $slug = $article->slug();
        
        return [
            'id' => $id ? $id->toInt() : null,
            'title' => $article->title()->toString(),
            'slug' => $slug ? $slug->toString() : null,
            'content' => $article->content()->toString(),
            'status' => $article->status()->toString(),
            'author_id' => $article->authorId()->toInt(),
            'created_at' => $article->createdAt()->format('Y-m-d H:i:s'),
            'updated_at' => $article->updatedAt()->format('Y-m-d H:i:s'),
            'uri' => $article->getUri(),
        ];
    }

    private function jsonResponse(array $data, int $status = 200): ResponseInterface
    {
        return new Response(
            $status,
            ['Content-Type' => 'application/json'],
            json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
        );
    }
}
