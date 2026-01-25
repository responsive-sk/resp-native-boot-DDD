<?php

namespace Blog\Database;

use Doctrine\DBAL\Connection;

class Database
{
    public static function getConnection(): Connection
    {
        // Backwards-compatible: return default application connection
        return DatabaseManager::getConnection();
    }
}
