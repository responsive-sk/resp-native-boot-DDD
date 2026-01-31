<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Mark;

use Blog\Domain\Blog\Entity\Article;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\Content;
use Blog\Domain\Blog\ValueObject\Title;
use Blog\Domain\User\ValueObject\UserId;
use Blog\Infrastructure\View\ViewRenderer;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class ArticlesController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private ViewRenderer $viewRenderer
    ) {
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $articles = $this->articleRepository->getAll();

        return $this->viewRenderer->renderResponse('mark.articles.index', [
            'articles' => $articles,
        ]);
    }

    public function show(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $article = $this->articleRepository->getById(ArticleId::fromInt($id));

        if (!$article) {
            // TODO: Return proper error response
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
        $data = (array) $request->getParsedBody();

        if (empty($data['title']) || empty($data['content'])) {
            return $this->viewRenderer->renderResponse('mark.articles.create', [
                'error' => 'Title and content are required',
                'title' => $data['title'] ?? '',
                'content' => $data['content'] ?? ''
            ]);
        }

        try {
            $title = Title::fromString($data['title']);
            $content = Content::fromString($data['content']);
            // TODO: Get actual logged in user ID. For now using a valid UUID placeholder.
            $authorId = UserId::fromString('00000000-0000-0000-0000-000000000001');

            $article = Article::create($title, $content, $authorId);
            $this->articleRepository->add($article);

            return new Response(302, ['Location' => '/mark/articles']);
        } catch (\InvalidArgumentException $e) {
            return $this->viewRenderer->renderResponse('mark.articles.create', [
                'error' => $e->getMessage(),
                'title' => $data['title'] ?? '',
                'content' => $data['content'] ?? ''
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
        $id = (int) $request->getAttribute('id');
        $articleId = ArticleId::fromInt($id);
        $article = $this->articleRepository->getById($articleId);

        if (!$article) {
            return $this->viewRenderer->renderResponse('error.404', [], 404);
        }

        $data = (array) $request->getParsedBody();

        if (empty($data['title']) || empty($data['content'])) {
            return $this->viewRenderer->renderResponse('mark.articles.edit', [
                'article' => $article,
                'error' => 'Title and content are required'
            ]);
        }

        try {
            $title = Title::fromString($data['title']);
            $content = Content::fromString($data['content']);

            $article->update($title, $content);
            $this->articleRepository->update($article);

            return new Response(302, ['Location' => '/mark/articles']);
        } catch (\InvalidArgumentException $e) {
            return $this->viewRenderer->renderResponse('mark.articles.edit', [
                'article' => $article,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $articleId = ArticleId::fromInt($id);

        $this->articleRepository->remove($articleId);

        return new Response(302, ['Location' => '/mark/articles']);
    }
}
