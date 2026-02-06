<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Mark;

use Blog\Domain\Blog\Entity\Category;
use Blog\Domain\Blog\Repository\CategoryRepository;
use Blog\Domain\Blog\ValueObject\CategoryName;
use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Infrastructure\View\ViewRenderer;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CategoryController extends BaseController
{
    public function __construct(
        \Psr\Container\ContainerInterface $container,
        \Blog\Core\UseCaseHandler $useCaseHandler,
        private CategoryRepository $categoryRepository,
        private ViewRenderer $viewRenderer,
        \Blog\Security\AuthorizationService $authorization
    ) {
        parent::__construct($container, $useCaseHandler, $authorization);
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $categories = $this->categoryRepository->getAll();

        return $this->viewRenderer->renderResponse('mark.categories.index', [
            'title' => 'Kategórie',
            'categories' => $categories,
        ]);
    }

    public function createForm(ServerRequestInterface $request): ResponseInterface
    {
        // SECURITY: Require MARK role for admin operations
        $user = $this->requireMarkWeb();
        if ($user === null) {
            return $this->htmlResponse('Access denied: MARK role required', 403);
        }

        return $this->viewRenderer->renderResponse('mark.categories.create', [
            'title' => 'Vytvoriť kategóriu',
        ]);
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        // SECURITY: Require MARK role for admin operations
        $user = $this->requireMarkWeb();
        if ($user === null) {
            return $this->htmlResponse('Access denied: MARK role required', 403);
        }

        $data = $request->getParsedBody();

        try {
            $name = CategoryName::fromString($data['name'] ?? '');
            $description = $data['description'] ?? null;

            // Check if category name already exists
            if ($this->categoryRepository->nameExists($name->toString())) {
                throw new \DomainException('Kategória s týmto názvom už existuje');
            }

            $category = Category::create($name, $description);
            $this->categoryRepository->add($category);

            return new Response(302, ['Location' => '/mark/categories']);
        } catch (\DomainException $e) {
            return $this->viewRenderer->renderResponse('mark.categories.create', [
                'title' => 'Vytvoriť kategóriu',
                'error' => $e->getMessage(),
                'name' => $data['name'] ?? '',
                'description' => $data['description'] ?? '',
            ]);
        }
    }

    public function editForm(ServerRequestInterface $request, string $id): ResponseInterface
    {
        $category = $this->categoryRepository->getById(
            \Blog\Domain\Blog\ValueObject\CategoryId::fromString($id)
        );

        if (!$category) {
            return new Response(404);
        }

        return $this->viewRenderer->renderResponse('mark.categories.edit', [
            'title' => 'Upraviť kategóriu',
            'category' => $category,
        ]);
    }

    public function update(ServerRequestInterface $request, string $id): ResponseInterface
    {
        $category = $this->categoryRepository->getById(
            \Blog\Domain\Blog\ValueObject\CategoryId::fromString($id)
        );

        if (!$category) {
            return new Response(404);
        }

        $data = $request->getParsedBody();

        try {
            $name = CategoryName::fromString($data['name'] ?? '');
            $description = $data['description'] ?? null;

            // Check if category name already exists (excluding current category)
            if ($this->categoryRepository->nameExists($name->toString(), $category->id())) {
                throw new \DomainException('Kategória s týmto názvom už existuje');
            }

            $category->update($name, $description);
            $this->categoryRepository->update($category);

            return new Response(302, ['Location' => '/mark/categories']);
        } catch (\DomainException $e) {
            return $this->viewRenderer->renderResponse('mark.categories.edit', [
                'title' => 'Upraviť kategóriu',
                'category' => $category,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function delete(ServerRequestInterface $request, string $id): ResponseInterface
    {
        $category = $this->categoryRepository->getById(
            \Blog\Domain\Blog\ValueObject\CategoryId::fromString($id)
        );

        if (!$category) {
            return new Response(404);
        }

        // TODO: Check if category has articles before deletion
        $this->categoryRepository->remove($category->id());

        return new Response(302, ['Location' => '/mark/categories']);
    }
}
