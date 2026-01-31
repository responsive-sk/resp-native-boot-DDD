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
        // Use Paths API for base directory
        $dataPath = \Blog\Infrastructure\Paths::dataPath();
        $basePath = \Blog\Infrastructure\Paths::basePath();

        // Get database paths from environment or use defaults
        // Convert relative paths to absolute
        $appDb = $_ENV['DB_PATH_APP'] ?? 'data/app.db';
        $articlesDb = $_ENV['DB_PATH_ARTICLES'] ?? 'data/articles.db';
        $usersDb = $_ENV['DB_PATH_USERS'] ?? 'data/users.db';
        $formsDb = $_ENV['DB_PATH_FORMS'] ?? 'data/forms.db';

        // Make paths absolute if they're relative
        $appDb = self::makeAbsolutePath($appDb, $basePath);
        $articlesDb = self::makeAbsolutePath($articlesDb, $basePath);
        $usersDb = self::makeAbsolutePath($usersDb, $basePath);
        $formsDb = self::makeAbsolutePath($formsDb, $basePath);

        return [
            'connections' => [
                'app' => [
                    'driver' => 'pdo_sqlite',
                    'path' => $appDb,
                ],
                'articles' => [
                    'driver' => 'pdo_sqlite',
                    'path' => $articlesDb,
                ],
                'users' => [
                    'driver' => 'pdo_sqlite',
                    'path' => $usersDb,
                ],
                'forms' => [
                    'driver' => 'pdo_sqlite',
                    'path' => $formsDb,
                ],
            ]
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
            if (isset($connectionConfig['path'])) {
                $dir = dirname($connectionConfig['path']);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
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
