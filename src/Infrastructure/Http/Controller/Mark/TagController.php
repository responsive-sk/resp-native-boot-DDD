<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Mark;

use Blog\Application\Blog\GetAllTags;
use Blog\Application\Blog\GetOrCreateTag;
use Blog\Domain\Blog\Repository\TagRepository;
use Blog\Domain\Blog\ValueObject\TagName;
use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Infrastructure\View\ViewRenderer;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class TagController extends BaseController
{
    public function __construct(
        \Psr\Container\ContainerInterface $container,
        \Blog\Core\UseCaseHandler $useCaseHandler,
        private TagRepository $tagRepository,
        private GetAllTags $getAllTags,
        private GetOrCreateTag $getOrCreateTag,
        private ViewRenderer $viewRenderer
    ) {
        parent::__construct($container, $useCaseHandler);
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        // ✅ SECURITY: Require MARK role for admin operations
        $user = $this->requireMarkWeb();
        if ($user === null) {
            return $this->htmlResponse('Access denied: MARK role required', 403);
        }

        $tags = ($this->getAllTags)();

        return $this->viewRenderer->renderResponse('mark.tags.index', [
            'title' => 'Tagy',
            'tags' => $tags,
        ]);
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        // ✅ SECURITY: Require MARK role for admin operations
        $user = $this->requireMarkWeb();
        if ($user === null) {
            return $this->htmlResponse('Access denied: MARK role required', 403);
        }

        $data = $request->getParsedBody();
        $name = trim($data['name'] ?? '');

        try {
            if (empty($name)) {
                throw new \DomainException('Názov tagu nemôže byť prázdny');
            }

            $tag = ($this->getOrCreateTag)($name);

            return new Response(302, ['Location' => '/mark/tags']);
        } catch (\DomainException $e) {
            $tags = ($this->getAllTags)();

            return $this->viewRenderer->renderResponse('mark.tags.index', [
                'title' => 'Tagy',
                'tags' => $tags,
                'error' => $e->getMessage(),
                'name' => $name,
            ]);
        }
    }

    public function delete(ServerRequestInterface $request, string $id): ResponseInterface
    {
        $tag = $this->tagRepository->getById(
            \Blog\Domain\Blog\ValueObject\TagId::fromString($id)
        );

        if (!$tag) {
            return new Response(404);
        }

        // TODO: Check if tag is used by articles before deletion
        $this->tagRepository->remove($tag->id());

        return new Response(302, ['Location' => '/mark/tags']);
    }
}
