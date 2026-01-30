<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Web;

use Blog\Application\Blog\CreateArticle;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Infrastructure\View\ViewRenderer;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class ArticleController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private CreateArticle $createArticle,
        private ViewRenderer $viewRenderer
    ) {
    }

    public function createForm(ServerRequestInterface $request): ResponseInterface
    {
        return $this->viewRenderer->renderResponse('article.create');
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        try {
            // Get author ID from session (UUID string)
            $authorId = $_SESSION['user_id'] ?? throw new \Exception('User not authenticated');

            $articleId = ($this->createArticle)(
                $data['title'] ?? '',
                $data['content'] ?? '',
                $authorId
            );

            // Redirect to the new article
            return new Response(302, ['Location' => '/blog/' . $articleId->toInt()]);

        } catch (\Exception $e) {
            return $this->viewRenderer->renderResponse('article.create', [
                'error' => $e->getMessage(),
                'title' => $data['title'] ?? '',
                'content' => $data['content'] ?? '',
            ], 400);
        }
    }

    public function editForm(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $article = $this->articleRepository->getById(\Blog\Domain\Blog\ValueObject\ArticleId::fromInt($id));

        if (!$article) {
            return new Response(404, ['Content-Type' => 'text/html'], 'Article not found');
        }

        return $this->viewRenderer->renderResponse('article.edit', [
            'article' => $article,
        ]);
    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $data = $request->getParsedBody();

        // TODO: Implement update logic
        // For now, redirect back
        return new Response(302, ['Location' => '/blog/' . $id]);
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');

        // TODO: Implement delete logic
        // For now, redirect to blog index
        return new Response(302, ['Location' => '/blog']);
    }
}
