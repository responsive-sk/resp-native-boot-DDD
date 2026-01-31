<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Mark;

use Blog\Domain\User\Entity\User;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Domain\User\ValueObject\Email;
use Blog\Domain\User\ValueObject\HashedPassword;
use Blog\Domain\User\ValueObject\UserId;
use Blog\Domain\User\ValueObject\UserRole;
use Blog\Infrastructure\View\ViewRenderer;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class UsersController
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
        $data = (array) $request->getParsedBody();

        $email = $data['email'] ?? '';
        $role = $data['role'] ?? 'ROLE_USER';
        $password = $data['password'] ?? '';

        if ($email === '' || $password === '') {
            return $this->viewRenderer->renderResponse('mark.users.create', [
                'error' => 'Email and password are required',
                'email' => $email,
                'role' => $role
            ]);
        }

        try {
            $emailObj = Email::fromString($email);
            if ($this->userRepository->emailExists($emailObj)) {
                throw new \InvalidArgumentException('Email is already registered.');
            }

            $passwordObj = HashedPassword::fromPlainPassword($password);
            $roleObj = UserRole::fromString($role);

            $user = User::register($emailObj, $passwordObj, $roleObj);
            $this->userRepository->save($user);

            return new Response(302, ['Location' => '/mark/users']);

        } catch (\InvalidArgumentException | \RuntimeException $e) {
            return $this->viewRenderer->renderResponse('mark.users.create', [
                'error' => $e->getMessage(),
                'email' => $email,
                'role' => $role
            ]);
        }
    }

    public function editForm(ServerRequestInterface $request): ResponseInterface
    {
        $userIdStr = $request->getAttribute('id');
        // Warning: Assuming UUID string is passed in URL
        try {
            $userId = UserId::fromString($userIdStr);
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
            $userId = UserId::fromString($userIdStr);
            $user = $this->userRepository->findById($userId);
        } catch (\Exception $e) {
            return $this->viewRenderer->renderResponse('error.404', [], 404);
        }

        if (!$user) {
            return $this->viewRenderer->renderResponse('error.404', [], 404);
        }

        $data = (array) $request->getParsedBody();

        try {
            // Handle Role Update - logic extracted for simplicity, ideally domain method updateRole(UserRole)
            if (isset($data['role'])) {
                $newRole = UserRole::fromString($data['role']);
                if ($newRole->isMark()) {
                    $user->promoteToMark();
                } else {
                    $user->demoteToUser();
                }
            }

            // Password update if provided
            if (!empty($data['password'])) {
                $newPassword = HashedPassword::fromPlainPassword($data['password']);
                $user->changePassword($newPassword);
            }

            // Note: Email update is tricky because of unique constraint check vs self, skipping for basic implementation or adding check if changed.
            // For now, let's assume email is read-only or we need to implement changeEmail domain logic which isn't there yet fully exposed?
            // User entity has generic constructor but no changeEmail method?
            // Checking Entity... no changeEmail method. So we skip email update for now or add it later.

            $this->userRepository->save($user);

            return new Response(302, ['Location' => '/mark/users']);

        } catch (\InvalidArgumentException $e) {
            return $this->viewRenderer->renderResponse('mark.users.edit', [
                'user' => $user,
                'error' => $e->getMessage()
            ]);
        }
    }
    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $userIdStr = $request->getAttribute('id');
        try {
            $userId = UserId::fromString($userIdStr);
            $this->userRepository->remove($userId);
        } catch (\Exception $e) {
            // Ignore error if ID is invalid or not found, just redirect back
        }

        return new Response(302, ['Location' => '/mark/users']);
    }
}
