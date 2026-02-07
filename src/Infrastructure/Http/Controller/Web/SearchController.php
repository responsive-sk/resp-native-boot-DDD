<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Web;

use Blog\Application\Blog\SearchArticles;
use Blog\Core\UseCaseHandler;
use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Infrastructure\View\ViewRenderer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SearchController extends BaseController
{
    public function __construct(
        ContainerInterface $container,
        UseCaseHandler $useCaseHandler,
        private ViewRenderer $viewRenderer,
        \Blog\Security\AuthorizationService $authorization
    ) {
        parent::__construct($container, $useCaseHandler, $authorization);
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $query = $request->getQueryParams()['q'] ?? '';
        $results = [];

        if ($query !== '') {
            $useCase = $this->useCaseHandler->get(SearchArticles::class);

            try {
                $result = $this->executeUseCase($request, $useCase, [
                    'query' => 'query:q',
                ], 'array');

                $results = $result['data']['articles'] ?? [];
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
