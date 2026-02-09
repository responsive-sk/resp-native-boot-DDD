<?php

declare(strict_types=1);

namespace Blog\Core;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFoundException extends Exception implements NotFoundExceptionInterface
{
}
