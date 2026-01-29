<?php

declare(strict_types=1);

namespace Blog\Infrastructure;

use ResponsiveSk\Slim4Paths\Paths as Slim4Paths;

/**
 * Paths wrapper for responsive-sk/slim4-paths
 * Provides backward compatibility with legacy Blog\Infrastructure\Paths API
 */
class Paths
{
    private static ?Slim4Paths $instance = null;

    /**
     * Get or create Slim4Paths instance
     */
    private static function getInstance(): Slim4Paths
    {
        if (self::$instance === null) {
            $basePath = self::detectBasePath();

            // Initialize with custom paths
            self::$instance = new Slim4Paths($basePath, [
                'data' => $basePath . '/data',
                'config' => $basePath . '/config',
                'public' => $basePath . '/public',
                'resources' => $basePath . '/resources',
                'src' => $basePath . '/src',
                'vendor' => $basePath . '/vendor',
            ]);
        }

        return self::$instance;
    }

    /**
     * Detect application base path
     */
    private static function detectBasePath(): string
    {
        // Check environment variable
        $envPath = $_ENV['APP_BASE_PATH'] ?? getenv('APP_BASE_PATH');
        if ($envPath && $envPath !== '') {
            return rtrim($envPath, DIRECTORY_SEPARATOR);
        }

        // Default: from current file location (src/Infrastructure/Paths.php)
        $candidate = dirname(__DIR__, 2);
        $real = realpath($candidate);
        return $real !== false ? $real : $candidate;
    }

    /**
     * Return the project base path
     */
    public static function basePath(): string
    {
        return self::getInstance()->getPath('base');
    }

    /**
     * Return data directory path
     */
    public static function dataPath(): string
    {
        return self::getInstance()->getPath('data');
    }

    /**
     * Return config directory path
     */
    public static function configPath(): string
    {
        return self::getInstance()->getPath('config');
    }

    /**
     * Return public directory path
     */
    public static function publicPath(): string
    {
        return self::getInstance()->getPath('public');
    }

    /**
     * Return resources directory path
     */
    public static function resourcesPath(): string
    {
        return self::getInstance()->getPath('resources');
    }

    /**
     * Join path segments (legacy compatibility)
     */
    public static function join(string $left, string $right): string
    {
        $left = rtrim($left, '/');
        $right = ltrim($right, '/');

        if ($right === '') {
            return $left === '' ? '/' : $left;
        }

        return $left . '/' . $right;
    }

    /**
     * Generate URL path with query parameters (legacy compatibility)
     */
    public static function path(string $nameOrPath, array $params = []): string
    {
        // If it's an absolute URL, return as-is
        if (preg_match('#^[a-zA-Z]+://#', $nameOrPath) === 1) {
            return $nameOrPath;
        }

        // Ensure leading slash
        $path = strpos($nameOrPath, '/') === 0 ? $nameOrPath : '/' . ltrim($nameOrPath, '/');

        if (!empty($params)) {
            $qs = http_build_query($params);
            return $path . '?' . $qs;
        }

        return $path;
    }

    /**
     * Get custom path by name
     */
    public static function get(string $name, string $fallback = ''): string
    {
        return self::getInstance()->getPath($name, $fallback);
    }

    /**
     * Set custom path
     */
    public static function set(string $name, string $path): void
    {
        self::getInstance()->setPath($name, $path);
    }

    /**
     * Get all paths
     */
    public static function all(): array
    {
        return self::getInstance()->all();
    }

    /**
     * Get underlying Slim4Paths instance for advanced usage
     */
    public static function slim4(): Slim4Paths
    {
        return self::getInstance();
    }
}