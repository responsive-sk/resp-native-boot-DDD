<?php

// resp-blog/src/Infrastructure/Http/Controller/Api/Mark/UsersApiController.php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Api\Mark;

use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UsersApiController extends BaseController
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getRecent(ServerRequestInterface $request): ResponseInterface
    {
        $limit = (int) ($request->getQueryParams()['limit'] ?? 10);
        $users = $this->userRepository->getRecentUsers($limit);

        $formattedUsers = array_map(function ($user) {
            return [
                'id' => $user->getId()->value(),
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'role' => $user->getRole(),
                'createdAt' => $user->getCreatedAt()->format('c'),
                'lastLoginAt' => $user->getLastLoginAt()
                    ? $user->getLastLoginAt()->format('c') : null,
                'isActive' => $user->isActive(),
            ];
        }, $users);

        return $this->json($formattedUsers);
    }
}
