<?php

declare(strict_types=1);

namespace Blog\Domain\User\Exception;

use Blog\Domain\User\ValueObject\Email;

final class EmailAlreadyExistsException extends \DomainException
{
    public static function withEmail(Email $email): self
    {
        return new self(sprintf('Email %s už existuje', $email->toString()));
    }
}
