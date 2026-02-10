<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Blog\Core\ServiceNotFoundException;

return function (): ContainerInterface {
    // Načti sloučené services
    $services = require __DIR__ . '/services_ddd.php';

    // Configuration
    $services['config'] = function () {
        $appConfig = require __DIR__ . '/app.php';

        return array_merge(
            $appConfig,
            [
                'cloudinary' => require __DIR__ . '/cloudinary.php',
                'database' => require __DIR__ . '/database.php',
            ]
        );
    };

    return new class ($services) implements ContainerInterface {
        private array $services;
        private array $instances = [];

        public function __construct(array $services)
        {
            $this->services = $services;
        }

        public function get(string $id)
        {
            if (!isset($this->services[$id])) {
                throw new ServiceNotFoundException("Service '$id' not found in container");
            }

            if (!isset($this->instances[$id])) {
                $factory = $this->services[$id];
                $this->instances[$id] = is_callable($factory) ? $factory($this) : $factory;
            }

            return $this->instances[$id];
        }

        public function has(string $id): bool
        {
            return isset($this->services[$id]);
        }
    };
};
