<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 */

namespace zeroline\MiniLoom\Tests\Data;

use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\Data\Database\SQL\ConnectionManager;
use zeroline\MiniLoom\Data\Database\SQL\Connection;
use zeroline\MiniLoom\Data\Database\SQL\DatabaseType;
use SQLite3;

class ConnectionManagerTest extends TestCase 
{
    const DB_FILENAME = 'tests/assets/Data/db.sqlite';
    const CREATE_TABLE_01_SQL = 'CREATE TABLE IF NOT EXISTS `test_table_01` (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT,
        `name` TEXT NOT NULL,
        `description` TEXT NOT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    );';
    protected static $db;

    public static function setUpBeforeClass(): void
    {
        static::$db = new SQLite3(self::DB_FILENAME);
    }

    public static function tearDownAfterClass(): void
    {
        unlink(self::DB_FILENAME);
    }

    public function testDefaultConnection()
    {
        $this->assertNull(ConnectionManager::getDefaultConnection());

        ConnectionManager::setDefaultConnection(new Connection(
            DatabaseType::SQLITE3,
            self::DB_FILENAME,
            null,
            null,
            null,
            null,
            null
        ));

        $this->assertInstanceOf(Connection::class, ConnectionManager::getDefaultConnection());
    }

    public function testDifferentConnection() 
    {
        $this->assertNull(ConnectionManager::getConnection('test'));

        ConnectionManager::addConnection('test', new Connection(
            DatabaseType::SQLITE3,
            self::DB_FILENAME,
            null,
            null,
            null,
            null,
            null
        ));

        $this->assertInstanceOf(Connection::class, ConnectionManager::getConnection('test'));
    }

    public function testInvalidConnection()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Connection key "default" is not allowed.');

        ConnectionManager::addConnection('default', new Connection(
            DatabaseType::SQLITE3,
            self::DB_FILENAME,
            null,
            null,
            null,
            null,
            null
        ));
    }
}