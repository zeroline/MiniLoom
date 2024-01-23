<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 */

namespace zeroline\MiniLoom\Data\Database\SQL;

use zeroline\MiniLoom\Data\Database\SQL\Connection as Connection;

class ConnectionManager
{
    private const DEFAULT_CONNECTION_NAME = 'default';
    private static $connections = array();
    
    public static function setDefaultConnection(Connection $connection): void
    {
        static::$connections[self::DEFAULT_CONNECTION_NAME] = $connection;
    }

    public static function getDefaultConnection(): ?Connection
    {
        return static::getConnection(self::DEFAULT_CONNECTION_NAME);
    }

    public static function addConnection(string $key, Connection $connection): void
    {
        if($key == self::DEFAULT_CONNECTION_NAME) {
            throw new \Exception('Connection key "' . $key . '" is not allowed.');
        }
        static::$connections[$key] = $connection;
    }

    public static function getConnection(string $key): ?Connection
    {
        if (array_key_exists($key, static::$connections)) {
            return static::$connections[$key];
        }
        return null;
    }
}
