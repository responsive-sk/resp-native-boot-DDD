<?php

declare(strict_types=1);

namespace Blog\Security\Exception;

use RuntimeException;

class CsrfTokenException extends RuntimeException
{
    public static function invalid(): self
    {
        return new self('Invalid or missing CSRF token. Please refresh the page and try again.');
    }
}
