<?php
// config/session.php
return [
    // === CORE SESSION SETTINGS ===
    'name' => $_ENV['SESSION_NAME'] ?? 'app_session', // Session cookie name

    // Cookie settings (pre slim4-session)
    'cookie_params' => [
        'lifetime' => (int) ($_ENV['SESSION_LIFETIME'] ?? 86400), // Default 24h if not set
        'path' => '/',
        'domain' => $_ENV['SESSION_DOMAIN'] ?? '',
        'secure' => ($_ENV['APP_ENV'] ?? 'development') === 'production',
        'httponly' => true,
        'samesite' => 'Lax',
    ],

    // === APPLICATION SPECIFIC ===
    'timeout' => [
        'default' => 1800,     // 30 min - verejné stránky
        'mark' => 7200,        // 2 hodiny - mark/admin panel  
        'api' => 86400,        // 24 hodín - API tokeny
    ],

    'fingerprint' => [
        'enabled' => true,
        'components' => ['user_agent', 'ip_subnet'], // Bind to UA and IP subnet
        'salt' => $_ENV['SESSION_FINGERPRINT_SALT'] ?? 'default-secret-change-me',
    ],

    // === MARK ADMIN SPECIFIC ===
    'mark' => [
        'session_prefix' => 'mark_',
        'regenerate_on_login' => true,
        'require_fingerprint' => true,
    ],

    // === SECURITY ===
    'security' => [
        'regenerate_id' => true,
        'use_strict_mode' => true,
        'use_cookies' => true,
        'use_only_cookies' => true,
        'cookie_secure' => ($_ENV['APP_ENV'] ?? 'development') === 'production', // true for prod, false for dev
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'sid_length' => 48,
        'sid_bits_per_character' => 6,
    ],
];