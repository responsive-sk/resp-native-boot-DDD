<?php
// src/Domain/User/ValueObject/MarkRole.php
declare(strict_types=1);

namespace Blog\Domain\User\ValueObject;

enum MarkRole: string
{
    case VIEWER = 'viewer';      // Can view mark dashboard
    case EDITOR = 'editor';      // Can manage articles
    case MANAGER = 'manager';    // Can manage users
    case SUPER_MARK = 'super_mark'; // Full access

    public function hasPermission(string $permission): bool
    {
        return match ($this) {
            self::SUPER_MARK => true,
            self::MANAGER => in_array($permission, ['manage_users', 'manage_articles', 'view_audit', 'view_stats']),
            self::EDITOR => in_array($permission, ['manage_articles', 'view_articles', 'view_stats']),
            self::VIEWER => $permission === 'view_stats',
        };
    }

    public function canManageUsers(): bool
    {
        return match ($this) {
            self::SUPER_MARK, self::MANAGER => true,
            default => false,
        };
    }

    public function canManageArticles(): bool
    {
        return match ($this) {
            self::SUPER_MARK, self::MANAGER, self::EDITOR => true,
            default => false,
        };
    }

    public function canViewAudit(): bool
    {
        return match ($this) {
            self::SUPER_MARK, self::MANAGER => true,
            default => false,
        };
    }

    public function getDisplayName(): string
    {
        return match ($this) {
            self::SUPER_MARK => 'Super Mark',
            self::MANAGER => 'Mark Manager',
            self::EDITOR => 'Mark Editor',
            self::VIEWER => 'Mark Viewer',
        };
    }

    public static function fromUserRole(UserRole $userRole): self
    {
        return match ($userRole) {
            UserRole::SUPER_ADMIN => self::SUPER_MARK,
            UserRole::ADMIN => self::MANAGER,
            UserRole::EDITOR => self::EDITOR,
            UserRole::ROLE_MARK => self::MANAGER, // Default legacy MARK role mapping
            default => self::VIEWER,
        };
    }
}
