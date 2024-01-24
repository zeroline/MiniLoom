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
use zeroline\MiniLoom\Data\Database\SQL\SQLFileExecuter;
use zeroline\MiniLoom\Data\Database\SQL\ConnectionManager;
use PDO;

class SQLFileExecuterTest extends TestCase 
{
    const DB_FILENAME = 'tests/assets/Data/db.sqlite';
    const MIGRATION_FILENAME = 'tests/assets/Data/testSqlFile.sql';
    protected static $db;

    public static function setUpBeforeClass(): void
    {
        static::$db = new SQLite3(self::DB_FILENAME);
    }

    public static function tearDownAfterClass(): void
    {
        unlink(self::DB_FILENAME);
    }

    public function testFileExecution() 
    {
        $this->assertInstanceOf(SQLite3::class, static::$db);
        $connection = new Connection(DatabaseType::SQLITE3, self::DB_FILENAME, null, null, null, null, null);
        $this->assertInstanceOf(Connection::class, $connection);

        $connection->connect();

        ConnectionManager::setDefaultConnection($connection);

        $this->assertInstanceOf(Connection::class, ConnectionManager::getDefaultConnection());

        $executionResult = SQLFileExecuter::loadAndExecute(self::MIGRATION_FILENAME, true);
        
        $this->assertTrue($executionResult);        
    }
}