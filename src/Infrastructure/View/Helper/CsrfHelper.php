<?php

declare(strict_types=1);

namespace Blog\Infrastructure\View\Helper;

class CsrfHelper
{
    public static function field(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = $_SESSION['csrf_token'] ?? '';

        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }

    public static function token(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['csrf_token'] ?? '';
    }

    public static function meta(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = $_SESSION['csrf_token'] ?? '';

        return '<meta name="csrf-token" content="' . htmlspecialchars($token) . '">';
    }
}
