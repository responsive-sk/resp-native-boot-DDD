<?php

declare(strict_types=1);

namespace Blog\Security\Exception;

final class AuthorizationException extends \RuntimeException
{
    public static function notAuthorized(string $role): self
    {
        return new self(sprintf('User is not authorized. Required role: %s', $role), 403);
    }
}
