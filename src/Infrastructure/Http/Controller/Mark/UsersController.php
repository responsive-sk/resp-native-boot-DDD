<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Mark;

use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Domain\User\Entity\User;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Application\User\RegisterUser;
use Blog\Application\User\LoginUser;
use Blog\Application\User\UpdateUserRole;
use Blog\Infrastructure\View\ViewRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UsersController extends BaseController
{
    public function __construct(
        \Psr\Container\ContainerInterface $container,
        \Blog\Core\UseCaseHandler $useCaseHandler,
        private UserRepositoryInterface $userRepository,
        private ViewRenderer $viewRenderer
    ) {
        parent::__construct($container, $useCaseHandler);
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        // SECURITY: Require MARK role for admin operations
        $user = $this->requireMarkWeb();
        if ($user === null) {
            return $this->htmlResponse('Access denied: MARK role required', 403);
        }

        $users = $this->userRepository->findAll();

        return $this->viewRenderer->renderResponse('mark.users.index', [
            'users' => $users,
        ]);
    }

    public function createForm(ServerRequestInterface $request): ResponseInterface
    {
        // SECURITY: Require MARK role for admin operations
        $user = $this->requireMarkWeb();
        if ($user === null) {
            return $this->htmlResponse('Access denied: MARK role required', 403);
        }

        return $this->viewRenderer->renderResponse('mark.users.create', []);
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        // SECURITY: Require MARK role for admin operations
        $user = $this->requireMarkWeb();
        if ($user === null) {
            return $this->htmlResponse('Access denied: MARK role required', 403);
        }

        $useCase = $this->useCaseHandler->get(RegisterUser::class);

        try {
            $result = $this->executeUseCase($request, $useCase, [
                'email' => 'body:email',
                'password' => 'body:password'
            ], 'web');

            return $this->redirect('/mark/users');
        } catch (\Exception $e) {
            return $this->viewRenderer->renderResponse('mark.users.create', [
                'error' => $e->getMessage(),
                'email' => $request->getParsedBody()['email'] ?? '',
            ]);
        }
    }

    public function editForm(ServerRequestInterface $request): ResponseInterface
    {
        $userIdStr = $request->getAttribute('id');

        // Warning: Assuming UUID string is passed in URL
        try {
            $userId = \Blog\Domain\User\ValueObject\UserId::fromString($userIdStr);
            $user = $this->userRepository->findById($userId);
        } catch (\Exception $e) {
            $user = null;
        }

        if (!$user) {
            return $this->viewRenderer->renderResponse('error.404', [], 404);
        }

        return $this->viewRenderer->renderResponse('mark.users.edit', [
            'user' => $user,
        ]);
    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(UpdateUserRole::class);

        try {
            $result = $this->executeUseCase($request, $useCase, [
                'user_id' => 'route:id',
                'role' => 'body:role'
            ], 'web');

            return $this->redirect('/mark/users');
        } catch (\Exception $e) {
            // Get user data for form repopulation
            $userIdStr = $request->getAttribute('id');
            $user = null;

            try {
                $userId = \Blog\Domain\User\ValueObject\UserId::fromString($userIdStr);
                $user = $this->userRepository->findById($userId);
            } catch (\Exception $ex) {
                // User not found, will be handled in editForm
            }

            return $this->viewRenderer->renderResponse('mark.users.edit', [
                'user' => $user,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $userIdStr = $request->getAttribute('id');

        try {
            $userId = \Blog\Domain\User\ValueObject\UserId::fromString($userIdStr);
            $this->userRepository->remove($userId);

            return $this->redirect('/mark/users');
        } catch (\Exception $e) {
            return $this->htmlResponse('Error deleting user: ' . $e->getMessage(), 400);
        }
    }
}
