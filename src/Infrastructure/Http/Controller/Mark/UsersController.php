<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Mark;

use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Domain\User\Entity\User;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Application\User\RegisterUser;
use Blog\Application\User\LoginUser;
use Blog\Infrastructure\View\ViewRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class UsersController extends BaseController
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ViewRenderer $viewRenderer
    ) {
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $users = $this->userRepository->findAll();

        return $this->viewRenderer->renderResponse('mark.users.index', [
            'users' => $users,
        ]);
    }

    public function createForm(ServerRequestInterface $request): ResponseInterface
    {
        return $this->viewRenderer->renderResponse('mark.users.create', []);
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = $this->useCaseHandler->get(RegisterUser::class);
        
        try {
            $result = $this->executeUseCase($request, $useCase, [
                'email' => 'body:email',
                'password' => 'body:password',
                'role' => 'body:role'
            ], 'web');

            return $this->redirect('/mark/users');
        } catch (\Exception $e) {
            return $this->viewRenderer->renderResponse('mark.users.create', [
                'error' => $e->getMessage(),
                'email' => $request->getParsedBody()['email'] ?? '',
                'role' => $request->getParsedBody()['role'] ?? 'ROLE_USER',
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
        $userIdStr = $request->getAttribute('id');
        
        try {
            $userId = \Blog\Domain\User\ValueObject\UserId::fromString($userIdStr);
            $user = $this->userRepository->findById($userId);
            
            if (!$user) {
                return $this->viewRenderer->renderResponse('error.404', [], 404);
            }

            // TODO: Implement user update logic
            return $this->redirect('/mark/users');
        } catch (\Exception $e) {
            return $this->htmlResponse('Error updating user: ' . $e->getMessage(), 400);
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
