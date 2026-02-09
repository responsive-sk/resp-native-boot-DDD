<?php

namespace Blog\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use InvalidArgumentException;

class DatabaseManager
{
    /** @var array<string, Connection> */
    private static array $connections = [];

    /**
     * @return array{connections: array<string, array{driver: string, path: string}>}
     */
    private static function getConfig(): array
    {
        $configFile = \Blog\Infrastructure\Paths::configPath() . '/database.php';

        if (file_exists($configFile) && is_readable($configFile)) {
            $config = require $configFile;
            if (is_array($config) && isset($config['connections'])) {
                return $config;
            }
        }

        // Fallback to environment variables if config file is missing or invalid
        $basePath = \Blog\Infrastructure\Paths::basePath();

        $appDb = $_ENV['DB_PATH_APP'] ?? 'data/app';
        $articlesDb = $_ENV['DB_PATH_ARTICLES'] ?? 'data/articles';
        $usersDb = $_ENV['DB_PATH_USERS'] ?? 'data/users';
        $formsDb = $_ENV['DB_PATH_FORMS'] ?? 'data/forms';

        $dbExtension = $_ENV['DB_EXTENSION'] ?? '.db';

        if (!str_ends_with($appDb, $dbExtension)) {
            $appDb .= $dbExtension;
        }
        if (!str_ends_with($articlesDb, $dbExtension)) {
            $articlesDb .= $dbExtension;
        }
        if (!str_ends_with($usersDb, $dbExtension)) {
            $usersDb .= $dbExtension;
        }
        if (!str_ends_with($formsDb, $dbExtension)) {
            $formsDb .= $dbExtension;
        }

        $appDb = self::makeAbsolutePath($appDb, $basePath);
        $articlesDb = self::makeAbsolutePath($articlesDb, $basePath);
        $usersDb = self::makeAbsolutePath($usersDb, $basePath);
        $formsDb = self::makeAbsolutePath($formsDb, $basePath);

        return [
            'connections' => [
                'app' => ['driver' => 'pdo_sqlite', 'path' => $appDb],
                'articles' => ['driver' => 'pdo_sqlite', 'path' => $articlesDb],
                'users' => ['driver' => 'pdo_sqlite', 'path' => $usersDb],
                'forms' => ['driver' => 'pdo_sqlite', 'path' => $formsDb],
            ],
        ];
    }

    public static function getConnection(string $name = 'app'): Connection
    {
        if (!isset(self::$connections[$name])) {
            $config = self::getConfig();

            if (!isset($config['connections'][$name])) {
                throw new InvalidArgumentException("Database connection '$name' not configured.");
            }

            $connectionConfig = $config['connections'][$name];

            // Ensure directory exists for SQLite
            if ($connectionConfig['path'] !== '') {
                $dir = dirname($connectionConfig['path']);

                if (!is_dir($dir)) {
                    mkdir($dir, 0o755, true);
                }
            }

            self::$connections[$name] = DriverManager::getConnection($connectionConfig);
        }

        return self::$connections[$name];
    }

    public static function closeConnection(string $name): void
    {
        unset(self::$connections[$name]);
    }

    public static function closeAllConnections(): void
    {
        self::$connections = [];
    }

    /**
     * Convert relative path to absolute path
     */
    private static function makeAbsolutePath(string $path, string $basePath): string
    {
        // If path is already absolute, return as-is
        if (str_starts_with($path, '/') || preg_match('/^[A-Z]:/i', $path)) {
            return $path;
        }

        // Otherwise, make it relative to basePath
        return $basePath . '/' . $path;
    }
}
