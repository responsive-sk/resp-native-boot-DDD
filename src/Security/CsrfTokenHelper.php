<?php

declare(strict_types=1);

namespace Blog\Security;

final class CsrfTokenHelper
{
    public function __construct(
        private readonly CsrfProtection $csrfProtection
    ) {}

    /**
     * Generate HTML hidden input field with CSRF token
     */
    public function generateHiddenInput(): string
    {
        $token = $this->csrfProtection->getToken();

        return sprintf(
            '<input type="hidden" name="csrf_token" value="%s">',
            htmlspecialchars($token, ENT_QUOTES, 'UTF-8')
        );
    }

    /**
     * Generate meta tag for CSRF token (for AJAX requests)
     */
    public function generateMetaTag(): string
    {
        $token = $this->csrfProtection->getToken();

        return sprintf(
            '<meta name="csrf-token" content="%s">',
            htmlspecialchars($token, ENT_QUOTES, 'UTF-8')
        );
    }

    /**
     * Get raw CSRF token value
     */
    public function getToken(): string
    {
        return $this->csrfProtection->getToken();
    }

    /**
     * Generate JavaScript snippet for CSRF token handling
     */
    public function generateJsSnippet(): string
    {
        $token = $this->csrfProtection->getToken();

        return sprintf(
            '<script>window.csrfToken = "%s";</script>',
            htmlspecialchars($token, ENT_QUOTES, 'UTF-8')
        );
    }

    /**
     * Generate complete CSRF protection for HTML forms
     */
    public function generateFormProtection(): string
    {
        return $this->generateHiddenInput() . "\n" . $this->generateJsSnippet();
    }
}
