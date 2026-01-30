<?php

declare(strict_types=1);

namespace Blog\Security\Exception;

final class AuthenticationException extends \RuntimeException
{
    public static function notAuthenticated(): self
    {
        return new self('User is not authenticated.', 401);
    }
}
