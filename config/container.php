<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

return function (): ContainerInterface {
    $services = require __DIR__ . '/services_ddd.php';

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
                throw new class ($id) extends Exception implements NotFoundExceptionInterface {
                    public function __construct(string $id)
                    {
                        parent::__construct("Service '$id' not found in container");
                    }
                };
            }

            if (!isset($this->instances[$id])) {
                $factory = $this->services[$id];
                $this->instances[$id] = $factory($this);
            }

            return $this->instances[$id];
        }

        public function has(string $id): bool
        {
            return isset($this->services[$id]);
        }
    };
};
