<?php

declare(strict_types=1);

namespace Blog\Security;

class Authorization
{
    private static function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function isAuthenticated(): bool
    {
        self::ensureSession();
        return isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
    }

    public static function getUser(): ?array
    {
        self::ensureSession();
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'],
            'role' => $_SESSION['user_role'],
        ];
    }

    public static function hasRole(string $role): bool
    {
        $user = self::getUser();
        return $user !== null && $user['role'] === $role;
    }

    public static function isMark(): bool
    {
        return self::hasRole('ROLE_MARK');
    }

    public static function requireAuth(): void
    {
        if (!self::isAuthenticated()) {
            header('Location: /login');
            exit;
        }
    }

    public static function requireRole(string $role): void
    {
        self::requireAuth();

        if (!self::hasRole($role)) {
            header('Location: /blog');
            exit;
        }
    }

    public static function requireMark(): void
    {
        self::requireRole('ROLE_MARK');
    }
}