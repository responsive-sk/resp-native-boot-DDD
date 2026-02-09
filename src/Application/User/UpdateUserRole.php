<?php

declare(strict_types=1);

namespace Blog\Application\User;

use Blog\Core\BaseUseCase;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Domain\User\ValueObject\UserId;
use Blog\Domain\User\ValueObject\UserRole;

final class UpdateUserRole extends BaseUseCase
{
    public function __construct(
        private UserRepositoryInterface $users
    ) {}

    public function execute(array $input): array
    {
        $this->validate($input);

        $userId = UserId::fromString($input['user_id']);
        $newRole = UserRole::fromString($input['role']);

        $user = $this->users->findById($userId);

        if (!$user) {
            throw new \DomainException('User not found');
        }



        // Update user role
        if ($newRole->isMark()) {
            $user->promoteToMark();
        } elseif ($newRole->isUser()) {
            $user->demoteToUser();
        }
        $this->users->save($user);

        return $this->success([
            'user' => [
                'id' => $user->id()->toString(),
                'email' => $user->email()->toString(),
                'role' => $user->role()->toString(),
                'updated_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    protected function validate(array $input): void
    {
        if (empty($input['user_id'])) {
            throw new \InvalidArgumentException('User ID is required');
        }

        if (empty($input['role'])) {
            throw new \InvalidArgumentException('Role is required');
        }

        if (!in_array($input['role'], ['ROLE_USER', 'ROLE_MARK'])) {
            throw new \InvalidArgumentException('Invalid role specified');
        }

        // Validate UUID format
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $input['user_id'])) {
            throw new \InvalidArgumentException('Invalid user ID format');
        }
    }
}
