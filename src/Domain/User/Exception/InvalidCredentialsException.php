<?php

declare(strict_types=1);

namespace Blog\Domain\User\Exception;

final class InvalidCredentialsException extends \DomainException
{
    public static function create(): self
    {
        return new self('Nesprávny email alebo heslo');
    }
}
