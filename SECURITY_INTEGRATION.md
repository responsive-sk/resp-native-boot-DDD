<?php

// Middleware Registration Order Example
// config/middleware.php or equivalent

use Blog\Middleware\SessionMiddleware;
use Blog\Infrastructure\Http\Middleware\CsrfMiddleware;
use Blog\Middleware\AuthMiddleware;

// Register middleware in correct order
$app->pipe(SessionMiddleware::class);      // 1. Initialize session
$app->pipe(CsrfMiddleware::class);         // 2. Validate CSRF tokens  
$app->pipe(AuthMiddleware::class);         // 3. Check authentication

// Example usage in templates:

// 1. Hidden input field for forms:
// <?= $csrfHelper->generateHiddenInput() ?>

// 2. Meta tag for AJAX requests:
// <?= $csrfHelper->generateMetaTag() ?>

// 3. JavaScript snippet:
// <?= $csrfHelper->generateJsSnippet() ?>

// 4. Complete form protection:
// <?= $csrfHelper->generateFormProtection() ?>

// Example AJAX request with CSRF token:
/*
fetch('/api/some-endpoint', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': window.csrfToken
    },
    body: JSON.stringify(data)
});
*/
