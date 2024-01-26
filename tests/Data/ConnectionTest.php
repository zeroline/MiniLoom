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
use SQLite3;
use zeroline\MiniLoom\Data\Database\SQL\Connection;
use zeroline\MiniLoom\Data\Database\SQL\DatabaseType;
use PDO;

class ConnectionTest extends TestCase
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

    public function testConnectionToSqlite3()
    {
        $this->assertInstanceOf(SQLite3::class, static::$db);
        $connection = new Connection(DatabaseType::SQLITE3, self::DB_FILENAME, null, null, null, null, null);
        $this->assertInstanceOf(Connection::class, $connection);

        $connection->connect();

        $createTable01Result = $connection->execute(self::CREATE_TABLE_01_SQL);
        $this->assertTrue($createTable01Result);

        // drop table
        $dropTable01Result = $connection->execute('DROP TABLE IF EXISTS `test_table_01`;');
        $this->assertTrue($dropTable01Result);
    }

    public function testCRUD()
    {
        $connection = new Connection(DatabaseType::SQLITE3, self::DB_FILENAME, null, null, null, null, null);
        $this->assertInstanceOf(Connection::class, $connection);

        $connection->connect();

        $createTable01Result = $connection->execute(self::CREATE_TABLE_01_SQL);
        $this->assertTrue($createTable01Result);

        // insert
        $insertResult = $connection->execute('INSERT INTO `test_table_01` (`name`, `description`) VALUES (\'test\', \'test\');');
        $this->assertTrue($insertResult);

        // select
        $selectResult = $connection->execute('SELECT * FROM `test_table_01`;');
        $this->assertTrue($insertResult);

        // update
        $updateResult = $connection->execute('UPDATE `test_table_01` SET `name` = \'test2\' WHERE `name` = \'test\';');
        $this->assertTrue($updateResult);

        // delete
        $deleteResult = $connection->execute('DELETE FROM `test_table_01` WHERE `name` = \'test2\';');
        $this->assertTrue($deleteResult);

        // drop table
        $dropTable01Result = $connection->execute('DROP TABLE IF EXISTS `test_table_01`;');
        $this->assertTrue($dropTable01Result);
    }
}
