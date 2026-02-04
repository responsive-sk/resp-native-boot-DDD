<?php

declare(strict_types=1);

namespace Blog\Domain\Common\Exception;

class ResourceNotFoundException extends \RuntimeException
{
    public function __construct(string $message = "Resource not found", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
