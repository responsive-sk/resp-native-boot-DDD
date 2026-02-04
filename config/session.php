<?php

// config/session.php
return [
    'timeout' => [
        'default' => 1800,     // 30 min - verejné stránky
        'mark' => 7200,        // 2 hodiny - mark/admin panel
        'api' => 86400,        // 24 hodín - API tokeny
    ],
    'fingerprint' => [
        'enabled' => true,
        'components' => ['user_agent'], // Bez IP kvôli mobile data/VPN
        'salt' => $_ENV['SESSION_FINGERPRINT_SALT'] ?? 'default-secret-change-me',
    ],
    'cookie' => [
        'httponly' => true,
        'secure' => $_ENV['APP_ENV'] === 'production',
        'samesite' => 'Lax',
        'domain' => $_ENV['SESSION_DOMAIN'] ?? null,
    ],
];
