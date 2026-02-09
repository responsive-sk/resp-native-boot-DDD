<?php
// src/Domain/User/ValueObject/UserRole.php
declare(strict_types=1);

namespace Blog\Domain\User\ValueObject;

enum UserRole: string
{
    case EDITOR = 'editor';
    case USER = 'user';
    case GUEST = 'guest';
    // Backwards compatibility
    case ROLE_USER = 'ROLE_USER';
    case ROLE_MARK = 'ROLE_MARK';
    // Admin role is disabled/banned
    // case SUPER_ADMIN = 'super_admin';
    // case ADMIN = 'admin';

    public function isMark(): bool
    {
        return match ($this) {
            self::ROLE_MARK => true,
            default => false,
        };
    }

    public function isUser(): bool
    {
        return match ($this) {
            self::USER, self::ROLE_USER => true,
            default => false,
        };
    }

    public function hasPermission(string $permission): bool
    {
        // MARK role has all permissions (replaces admin)
        // EDITOR has limited permissions
        // USER has basic permissions
        return match ($permission) {
            'admin.access' => $this === self::EDITOR || $this === self::ROLE_MARK, // Editors and Mark can access admin
            'content.create' => $this === self::EDITOR || $this === self::USER || $this === self::ROLE_MARK, // Editors, Users, Mark can create
            'content.edit' => $this === self::EDITOR || $this === self::USER || $this === self::ROLE_MARK, // Editors, Users, Mark can edit
            'content.delete' => $this === self::EDITOR || $this === self::ROLE_MARK, // Only Editors and Mark can delete
            'user.manage' => $this === self::EDITOR || $this === self::ROLE_MARK, // Only Editors and Mark can manage users
            default => false,
        };
    }

    public static function fromString(string $role): self
    {
        return self::tryFrom($role) ?? match ($role) {
            'ROLE_USER' => self::ROLE_USER,
            'ROLE_MARK' => self::ROLE_MARK,
            'super_admin' => throw new \InvalidArgumentException("Admin role is disabled"),
            'admin' => throw new \InvalidArgumentException("Admin role is disabled"),
            default => throw new \InvalidArgumentException("Invalid role: $role"),
        };
    }

    // Legacy support methods
    public static function user(): self
    {
        return self::ROLE_USER;
    }

    public static function mark(): self
    {
        return self::ROLE_MARK;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
