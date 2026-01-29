<?php
namespace Blog\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use InvalidArgumentException;

class DatabaseManager
{
    private static array $connections = [];

    private static function getConfig(): array
    {
        // Use Paths API for base directory
        $dataPath = \Blog\Infrastructure\Paths::dataPath();

        // Get database paths from environment or use defaults
        $appDb = $_ENV['DB_PATH_APP'] ?? $dataPath . '/app.db';
        $articlesDb = $_ENV['DB_PATH_ARTICLES'] ?? $dataPath . '/articles.db';
        $usersDb = $_ENV['DB_PATH_USERS'] ?? $dataPath . '/users.db';
        $formsDb = $_ENV['DB_PATH_FORMS'] ?? $dataPath . '/forms.db';

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
}
