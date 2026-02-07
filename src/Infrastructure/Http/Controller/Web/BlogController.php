<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Web;

use Blog\Core\UseCaseHandler;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\Repository\CategoryRepository;
use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Blog\ValueObject\CategorySlug;
use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Infrastructure\View\ViewRenderer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class BlogController extends BaseController
{
    public function __construct(
        ContainerInterface $container,
        UseCaseHandler $useCaseHandler,
        private ArticleRepository $articleRepository,
        private CategoryRepository $categoryRepository,
        private ViewRenderer $viewRenderer,
        \Blog\Security\AuthorizationService $authorization
    ) {
        parent::__construct($container, $useCaseHandler, $authorization);
    }

    public function home(ServerRequestInterface $request): ResponseInterface
    {
        return $this->viewRenderer->renderResponse('home');
    }

    public function about(ServerRequestInterface $request): ResponseInterface
    {
        return $this->viewRenderer->renderResponse('about');
    }

    public function contact(ServerRequestInterface $request): ResponseInterface
    {
        return $this->viewRenderer->renderResponse('contact');
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\GetAllArticles::class);

        $result = $this->executeUseCase($request, $useCase, [], 'array');
        $articles = $result['data']['articles'] ?? [];

        $categories = $this->categoryRepository->getAll();
        $page = (int) ($request->getQueryParams()['page'] ?? 1);

        return $this->viewRenderer->renderResponse('blog.index', [
            'articles' => $articles,
            'categories' => $categories,
            'blogCategories' => array_map(fn ($cat) => $cat->slug()->toString(), $categories),
            'page' => $page,
        ]);
    }

    public function show(ServerRequestInterface $request): ResponseInterface
    {
        $idRaw = $request->getAttribute('id');

        // Basic validation + conversion to value object
        if (!is_scalar($idRaw) || !ctype_digit((string) $idRaw) || (int) $idRaw <= 0) {
            return $this->htmlResponse('Invalid or missing article ID', 404);
        }

        $id = ArticleId::fromInt((int) $idRaw);

        $article = $this->articleRepository->getById($id);

        if ($article === null) {
            return $this->htmlResponse('Article not found (ID: ' . $idRaw . ')', 404);
        }

        return $this->viewRenderer->renderResponse('blog.show', [
            'article' => $article,
        ]);
    }

    public function showBySlug(ServerRequestInterface $request): ResponseInterface
    {
        $slugRaw = $request->getAttribute('slug');

        if (empty($slugRaw)) {
            return $this->htmlResponse('Slug is required', 400);
        }

        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\GetArticleBySlug::class);

        try {
            $result = $this->executeUseCase($request, $useCase, [
                'slug' => 'route:slug',
            ], 'array');

            return $this->viewRenderer->renderResponse('blog.show', [
                'article' => $result['data']['article'],
            ]);
        } catch (\Exception $e) {
            return $this->htmlResponse('Article not found', 404);
        }
    }

    public function showCategory(ServerRequestInterface $request, string $slug): ResponseInterface
    {
        if (empty(trim($slug))) {
            return $this->htmlResponse('Invalid or missing category slug', 404);
        }

        $categorySlug = CategorySlug::fromString($slug);
        $category = $this->categoryRepository->getBySlug($categorySlug);

        if ($category === null) {
            return $this->htmlResponse('Category not found: ' . htmlspecialchars($slug), 404);
        }

        $articles = $this->articleRepository->getByCategory($category->id());

        return $this->viewRenderer->renderResponse('blog.category', [
            'category' => $category,
            'articles' => $articles,
            'blogCategories' => array_map(fn ($cat) => $cat->slug()->toString(), $this->categoryRepository->getAll()),
        ]);
    }
}
