<?php
// config/container.php - EXTREMNE ZJEDNODUŠENÁ VERZIA

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

return function (): ContainerInterface {
    // 1. Načítaj základné služby
    $services = require __DIR__ . '/services_ddd.php';
    
    // 2. Načítaj debugbar konfiguráciu
    $debugbarConfig = require __DIR__ . '/debugbar.php';
    
    // 3. Pridaj config service
    $services['config'] = function () {
        return [
            'database' => require __DIR__ . '/database.php',
            'session' => require __DIR__ . '/session.php',
            'cloudinary' => require __DIR__ . '/cloudinary.php',
            'debugbar' => $debugbarConfig['debugbar'] ?? [],
        ];
    };
    
    // 4. Pridaj nové služby pre FÁZU 4
    $services += [
        'use_case_handler' => fn () => new \Blog\Core\UseCaseHandler($this),
        'image_repository' => fn () => new \Blog\Infrastructure\Persistence\Doctrine\DoctrineImageRepository(
            $this->get('database')
        ),
        'image_factory' => fn () => new \Blog\Domain\Image\Factory\ImageFactory(),
    ];
    
    // 5. Pridaj DebugBar služby ak sú potrebné
    if ($debugbarConfig['debugbar']['enabled'] ?? false) {
        // DebugBar middleware
        $services[\ResponsiveSk\PhpDebugBarMiddleware\DebugBarMiddleware::class] = 
            \ResponsiveSk\PhpDebugBarMiddleware\DebugBarMiddlewareFactory::class;
        
        // DebugBar assets handler
        $services[\ResponsiveSk\PhpDebugBarMiddleware\DebugBarAssetsHandler::class] = 
            \ResponsiveSk\PhpDebugBarMiddleware\DebugBarAssetsHandlerFactory::class;
    }
    
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
                
                // Ak je to string (factory class), vytvor inštanciu
                if (is_string($factory) && class_exists($factory)) {
                    $this->instances[$id] = new $factory();
                } else {
                    $this->instances[$id] = $factory($this);
                }
            }

            return $this->instances[$id];
        }

        public function has(string $id): bool
        {
            return isset($this->services[$id]);
        }
    };
};