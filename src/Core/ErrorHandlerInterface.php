<?php

declare(strict_types=1);

namespace Blog\Core;

use Psr\Http\Message\ResponseInterface;
use Throwable;

interface ErrorHandlerInterface
{
    public function handle(Throwable $exception): ResponseInterface;
}
