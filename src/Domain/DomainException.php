<?php
// src/Domain/DomainException.php
declare(strict_types=1);

namespace Blog\Domain;

abstract class DomainException extends \Exception
{
    public function __construct(string $message = "Domain error occurred", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
