<?php

declare(strict_types=1);

use Blog\Infrastructure\View\PlatesRenderer;
use Blog\Infrastructure\View\ViewRenderer;
use Psr\Container\ContainerInterface;

return [
    PlatesRenderer::class => function (ContainerInterface $c) {
        // Boot už inicializoval Paths, takže můžeme použít statickou metodu
        // Pro jistotu zajistíme, že Paths jsou inicializovány správně
        if (class_exists(\Blog\Infrastructure\Paths::class)) {
            try {
                $resourcesPath = \Blog\Infrastructure\Paths::resourcesPath();
            } catch (\Throwable $e) {
                // Fallback if Paths fail
                $resourcesPath = dirname(__DIR__, 2) . '/resources';
            }
        } else {
            $resourcesPath = dirname(__DIR__, 2) . '/resources';
        }

        // Debug
        // error_log("[VIEW] Creating PlatesRenderer with path: " . $resourcesPath);
    
        if (!is_dir($resourcesPath)) {
            // Pokud resources neexistují, zkusíme fallback na root/resources
            $rootDir = dirname(__DIR__, 2);
            $fallbackPath = $rootDir . '/resources';

            if (is_dir($fallbackPath)) {
                $resourcesPath = $fallbackPath;
                // error_log("[VIEW] Fallback to: " . $resourcesPath);
            } else {
                throw new RuntimeException(
                    "Resources directory not found: " . $resourcesPath .
                    "\nCurrent dir: " . getcwd()
                );
            }
        }

        return new PlatesRenderer($resourcesPath);
    },

    ViewRenderer::class => function (ContainerInterface $c) {
        // error_log("[VIEW] Creating ViewRenderer");
    
        return new ViewRenderer(
            $c->get(PlatesRenderer::class),
            $c->has('pages.config')
            ? $c->get('pages.config')
            : require __DIR__ . '/../app/pages.php'
        );
    },
];
