<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Web;

use Blog\Application\Blog\SearchArticles;
use Blog\Infrastructure\View\ViewRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class SearchController
{
    public function __construct(
        private SearchArticles $searchArticles,
        private ViewRenderer $viewRenderer
    ) {
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $query = $request->getQueryParams()['q'] ?? '';
        $results = [];

        if ($query !== '') {
            $results = ($this->searchArticles)($query);
        }

        return $this->viewRenderer->renderResponse('search.index', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}
