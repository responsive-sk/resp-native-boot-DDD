<?php
declare(strict_types=1);

return function () {
    $services = require __DIR__ . '/services_ddd.php';
    
    // Remove problematic debugbar reference
    $services['config'] = function () {
        return ['debugbar' => ['enabled' => false]];
    };
    
    return new class($services) {
        private array $services;
        private array $instances = [];
        
        public function __construct(array $services) {
            $this->services = $services;
        }
        
        public function get(string $id) {
            if (!isset($this->services[$id])) {
                throw new Exception("Service '$id' not found");
            }
            
            if (!isset($this->instances[$id])) {
                $factory = $this->services[$id];
                $this->instances[$id] = is_callable($factory) ? $factory($this) : $factory;
            }
            
            return $this->instances[$id];
        }
        
        public function has(string $id): bool {
            return isset($this->services[$id]);
        }
    };
};
