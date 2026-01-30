<?php

declare(strict_types=1);

namespace Blog\Core;

use RuntimeException;

/**
 * RouteNotFoundException - vyhadzuje sa keď route nebola nájdená
 */
final class RouteNotFoundException extends RuntimeException
{
    public function getStatusCode(): int
    {
        return 404;
    }
}
