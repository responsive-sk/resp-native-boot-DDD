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
        // Simple hardcoded configuration
        $baseDir = dirname(__DIR__, 2);
        
        return [
            'connections' => [
                'app' => [
                    'driver' => 'pdo_sqlite',
                    'path' => $baseDir . '/data/app.db',
                ],
                'articles' => [
                    'driver' => 'pdo_sqlite',
                    'path' => $baseDir . '/data/articles.db',
                ],
                'users' => [
                    'driver' => 'pdo_sqlite',
                    'path' => $baseDir . '/data/users.db',
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
