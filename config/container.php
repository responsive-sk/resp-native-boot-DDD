<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

return function () {
    $services = require __DIR__ . '/services_ddd.php';

    // Configuration
    $services['config'] = function () {
        return [
            'debugbar' => ['enabled' => false],
            'session' => require __DIR__ . '/session.php',
        ];
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
                throw new class () extends \Exception implements NotFoundExceptionInterface {
                    public function __construct(string $message)
                    {
                        parent::__construct($message);
                    }
                }("Service '$id' not found");
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
