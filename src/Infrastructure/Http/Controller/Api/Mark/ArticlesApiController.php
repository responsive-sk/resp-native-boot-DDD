<?php

// resp-blog/src/Infrastructure/Http/Controller/Api/Mark/ArticlesApiController.php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Api\Mark;

use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ArticlesApiController extends BaseController
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getRecent(ServerRequestInterface $request): ResponseInterface
    {
        $limit = (int) ($request->getQueryParams()['limit'] ?? 10);
        $articles = $this->articleRepository->getRecentArticles($limit);

        $formattedArticles = array_map(function ($article) {
            return [
                'id' => $article->getId()->value(),
                'title' => $article->getTitle(),
                'excerpt' => $article->getExcerpt(),
                'status' => $article->getStatus(),
                'createdAt' => $article->getCreatedAt()->format('c'),
                'publishedAt' => $article->getPublishedAt()
                    ? $article->getPublishedAt()->format('c') : null,
                'status' => $article->getStatus(),
            ];
        }, $articles);

        return $this->json($formattedArticles);
    }
}
