<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Web;

use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Application\Blog\SearchArticles;
use Blog\Infrastructure\View\ViewRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class SearchController extends BaseController
{
    public function __construct(
        private ViewRenderer $viewRenderer
    ) {
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $query = $request->getQueryParams()['q'] ?? '';
        $results = [];

        if ($query !== '') {
            $useCase = $this->useCaseHandler->get(SearchArticles::class);
            
            try {
                $result = $this->executeUseCase($request, $useCase, [
                    'query' => 'query:q'
                ], 'web');
                
                $results = $result['articles'] ?? [];
            } catch (\Exception $e) {
                $results = [];
            }
        }

        return $this->viewRenderer->renderResponse('search.index', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}
