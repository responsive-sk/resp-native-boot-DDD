<?php

declare(strict_types=1);

use Blog\Infrastructure\View\PlatesRenderer;
use Blog\Infrastructure\View\ViewRenderer;
use Psr\Container\ContainerInterface;
use Blog\Infrastructure\Paths; // Explicitly import Paths

return [
    PlatesRenderer::class => fn () => new PlatesRenderer(
        Paths::resourcesPath()
    ),

    ViewRenderer::class => fn (ContainerInterface $c) => new ViewRenderer(
        $c->get(PlatesRenderer::class),
        require __DIR__ . '/../pages.php'
    ),
];
