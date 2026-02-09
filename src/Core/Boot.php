<?php

declare(strict_types=1);

namespace Blog\Core;

use Psr\Container\ContainerInterface;
use Throwable;
use RuntimeException;
use Dotenv\Dotenv;

/**
 * Bootstrapping logic for the application
 */
final class Boot
{
    private static ?ContainerInterface $container = null;

    // Make constructor private to prevent instantiation
    private function __construct()
    {
    }

    public static function boot(): ContainerInterface
    {
        if (self::$container !== null) {
            return self::$container;
        }

        try {
            // 1. Configure initial PHP settings (encoding, timezone default)
            self::configurePhp();

            // 2. Load Environment Variables (.env)
            self::loadEnvironment();

            // 3. Initialize Paths
            // Assuming this class is in src/Core/Boot.php, project root is ../../
            $rootDir = dirname(__DIR__, 2);
            // Verify if we are indeed in the expected structure. If Boot.php is in src/Core, root is 2 levels up.
            // If it's used via vendor, this path might need adjustment or detection logic.
            // For now, hardcoding relative path for this project structure.
            if (!is_dir($rootDir . '/config')) {
                // Fallback or error - maybe we are in a different structure?
                // Let's trust dirname for now as per project structure.
            }

            if (class_exists(\Blog\Infrastructure\Paths::class)) {
                \Blog\Infrastructure\Paths::set('base', $rootDir);
            }

            // 4. Create Container
            self::$container = self::createContainer($rootDir);

            // 5. Initialize Services
            self::initializeServices(self::$container);

            return self::$container;

        } catch (Throwable $e) {
            self::handleBootError($e);
            exit(1);
        }
    }

    private static function configurePhp(): void
    {
        // Default safe settings
        mb_internal_encoding('UTF-8');
        mb_http_output('UTF-8');
        // Default timezone, can be overridden by env
        date_default_timezone_set('Europe/Bratislava');
    }

    private static function loadEnvironment(): void
    {
        $rootDir = dirname(__DIR__, 2);
        if (file_exists($rootDir . '/.env')) {
            $dotenv = Dotenv::createImmutable($rootDir);
            $dotenv->safeLoad();
        }

        // Apply defaults/fallbacks
        $_ENV['APP_ENV'] ??= 'development';
        $_ENV['APP_URL'] ??= 'http://localhost:8000';
        $_ENV['APP_DEBUG'] ??= $_ENV['APP_ENV'] === 'development' ? 'true' : 'false';

        // Set error reporting based on loaded env
        if (($_ENV['APP_DEBUG'] ?? 'false') === 'true') {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
        } else {
            error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
            ini_set('display_errors', '0');
            ini_set('display_startup_errors', '0');
        }

        // Update timezone if specified in ENV
        if (isset($_ENV['TIMEZONE'])) {
            date_default_timezone_set($_ENV['TIMEZONE']);
        }
    }

    private static function createContainer(string $rootDir): ContainerInterface
    {
        $configFile = $rootDir . '/config/container.php';
        if (!file_exists($configFile)) {
            throw new RuntimeException("Config file not found: $configFile");
        }

        $containerFactory = require $configFile;

        if (!is_callable($containerFactory)) {
            throw new RuntimeException("Container config file must return a callable.");
        }

        $container = $containerFactory();

        if (!$container instanceof ContainerInterface) {
            throw new RuntimeException('Container factory must return ContainerInterface');
        }

        return $container;
    }

    private static function initializeServices(ContainerInterface $container): void
    {
        // Initialize Authorization with Session
        if ($container->has(\ResponsiveSk\Slim4Session\SessionInterface::class)) {
            $session = $container->get(\ResponsiveSk\Slim4Session\SessionInterface::class);
            if (class_exists(\Blog\Security\Authorization::class)) {
                \Blog\Security\Authorization::setSession($session);
            }
        }
    }

    private static function handleBootError(Throwable $e): void
    {
        $appDebug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';

        if ($appDebug) {
            if (!headers_sent()) {
                header('Content-Type: text/html; charset=utf-8');
                http_response_code(500);
            }
            echo "<h1>Application Boot Error</h1>";
            echo "<pre>" . htmlspecialchars((string) $e) . "</pre>";
        } else {
            // Log it to error log
            error_log(sprintf(
                'Boot error: %s in %s:%d',
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));

            if (!headers_sent()) {
                http_response_code(500);
                header('Content-Type: text/plain; charset=utf-8');
            }
            echo 'Application startup failed.';
        }
    }
}
