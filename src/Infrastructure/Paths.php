<?php

namespace Blog\Infrastructure;

class Paths
{
    /**
     * Very small path helper: returns a path for a named route or raw path.
     * This vendored helper is intentionally minimal; replace with full package API later.
     */
    public static function path(string $nameOrPath, array $params = []): string
    {
        // If it's an absolute URL, return as-is
        if (preg_match('#^[a-zA-Z]+://#', $nameOrPath) === 1) {
            return $nameOrPath;
        }

        // If looks like a route name (no leading slash), keep as-is but ensure leading slash
        $path = strpos($nameOrPath, '/') === 0 ? $nameOrPath : '/' . ltrim($nameOrPath, '/');

        if (!empty($params)) {
            $qs = http_build_query($params);
            return self::join($path, '') . '?' . $qs;
        }

        return self::join($path, '');
    }

    /**
     * Safely join two path segments ensuring a single directory separator.
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
     * Internal config cache
     * @var array|null
     */
    private static $config = null;

    /**
     * Set runtime config overrides.
     */
    public static function setConfig(array $config): void
    {
        self::$config = $config + (self::$config ?? []);
    }

    /**
     * Get merged config (file + env + runtime overrides)
     */
    public static function getConfig(): array
    {
        if (self::$config !== null) {
            return self::$config;
        }

        $config = [];

        // load config file if present
        $cfgFile = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'paths.php';
        if (file_exists($cfgFile)) {
            $fileCfg = include $cfgFile;
            if (is_array($fileCfg)) {
                $config = $fileCfg;
            }
        }

        // environment overrides
        $envBase = getenv('APP_BASE_PATH');
        if ($envBase && $envBase !== '') {
            $config['base_path'] = rtrim($envBase, DIRECTORY_SEPARATOR);
        }
        $envData = getenv('APP_DATA_PATH');
        if ($envData && $envData !== '') {
            $config['data_path'] = rtrim($envData, DIRECTORY_SEPARATOR);
        }

        self::$config = $config;
        return self::$config;
    }

    /**
     * Return the project base path.
     */
        /**
     * Return the project base path.
     */
    public static function basePath(): string
    {
        $cfg = self::getConfig();
        if (!empty($cfg['base_path'])) {
            return rtrim($cfg['base_path'], DIRECTORY_SEPARATOR);
        }

        // Allow legacy env var fallback
        $env = getenv('APP_BASE_PATH');
        if ($env && $env !== '') {
            return rtrim($env, DIRECTORY_SEPARATOR);
        }

        // Default: from current file location (src/Infrastructure/Paths.php)
        $candidate = dirname(__DIR__, 2);
        $real = realpath($candidate);
        return $real !== false ? $real : $candidate;
    }

        // Allow legacy env var fallback
        $env = getenv('APP_BASE_PATH');
        if ($env && $env !== '') {
            return rtrim($env, DIRECTORY_SEPARATOR);
        }

        // Project root: src/Infrastructure/Paths.php, go up 2 levels
        $candidate = dirname(__DIR__, 2);
        $real = realpath($candidate);
        return $real !== false ? $real : $candidate;
    }

    /**
     * Return data directory path under project base.
     */
    public static function dataPath(): string
    {
        $cfg = self::getConfig();
        if (!empty($cfg['data_path'])) {
            return rtrim($cfg['data_path'], DIRECTORY_SEPARATOR);
        }

        return self::basePath() . DIRECTORY_SEPARATOR . 'data';
    }
}