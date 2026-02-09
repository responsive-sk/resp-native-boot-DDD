<?php

declare(strict_types=1);

return [
    'enabled' => filter_var($_ENV['RATE_LIMIT_ENABLED'] ?? 'true', FILTER_VALIDATE_BOOLEAN),

    'login' => [
        'enabled' => filter_var($_ENV['RATE_LIMIT_LOGIN_ENABLED'] ?? 'true', FILTER_VALIDATE_BOOLEAN),
        'attempts' => (int) ($_ENV['RATE_LIMIT_LOGIN_ATTEMPTS'] ?? 5),
        'window' => (int) ($_ENV['RATE_LIMIT_LOGIN_WINDOW'] ?? 900), // 15 minutes
        'lockout' => (int) ($_ENV['RATE_LIMIT_LOGIN_LOCKOUT'] ?? 1800), // 30 minutes
    ],

    'register' => [
        'enabled' => filter_var($_ENV['RATE_LIMIT_REGISTER_ENABLED'] ?? 'true', FILTER_VALIDATE_BOOLEAN),
        'attempts' => (int) ($_ENV['RATE_LIMIT_REGISTER_ATTEMPTS'] ?? 3),
        'window' => (int) ($_ENV['RATE_LIMIT_REGISTER_WINDOW'] ?? 3600), // 1 hour
        'lockout' => (int) ($_ENV['RATE_LIMIT_REGISTER_LOCKOUT'] ?? 3600), // 1 hour
    ],

    'default' => [
        'enabled' => filter_var($_ENV['RATE_LIMIT_DEFAULT_ENABLED'] ?? 'true', FILTER_VALIDATE_BOOLEAN),
        'attempts' => (int) ($_ENV['RATE_LIMIT_DEFAULT_ATTEMPTS'] ?? 100),
        'window' => (int) ($_ENV['RATE_LIMIT_DEFAULT_WINDOW'] ?? 3600), // 1 hour
        // 'lockout' not typically used for default
    ],
];
