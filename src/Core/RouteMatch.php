<?php

declare(strict_types=1);

namespace Blog\Core;

use Closure;

/**
 * RouteMatch - reprezentuje nájdenú route
 *
 * Obsahuje názov route, handler a parametre z URL
 */
final readonly class RouteMatch
{
    /**
     * @param string $name Názov route
     * @param Closure $handler Handler funkcia
     * @param array<string, string> $params URL parametre (napr. ['id' => '123'])
     */
    public function __construct(
        private string $name,
        private Closure $handler,
        private array $params = []
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHandler(): Closure
    {
        return $this->handler;
    }

    /**
     * @return array<string, string>
     */
    public function getParams(): array
    {
        return $this->params;
    }
}

