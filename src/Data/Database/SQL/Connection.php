<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 */

namespace zeroline\MiniLoom\Data\Database\SQL;

use PDO;
use PDOException;
use PDOStatement;
use Throwable;
use zeroline\MiniLoom\Data\Database\SQL\DatabaseType as DatabaseType;
use RuntimeException;

class Connection
{
    private const LIMIT_STYLE_TOP_N = "top";
    private const LIMIT_STYLE_LIMIT = "limit";

    /**
     *
     * @var null|PDOStatement
     */
    private ?PDOStatement $lastStatement = null;

    /**
     *
     * @var null|PDO
     */
    private ?PDO $connection = null;

    /**
     *
     * @param DatabaseType $dbType
     * @param string $databaseName
     * @param null|string $host
     * @param null|int $port
     * @param null|string $username
     * @param null|string $password
     * @param null|array<mixed, mixed> $options
     * @return void
     */
    public function __construct(
        protected DatabaseType $dbType,
        protected string $databaseName,
        protected ?string $host,
        protected ?int $port,
        protected ?string $username,
        protected ?string $password,
        protected ?array $options
    ) {
    }

    /**
     *
     * @return void
     * @throws PDOException
     */
    public function connect() : void
    {
        $connectionString = '';
        switch ($this->dbType) {
            case DatabaseType::SQLITE:
            case DatabaseType::SQLITE2:
            case DatabaseType::SQLITE3:
                $connectionString = 'sqlite:' . $this->databaseName;
                break;
            default:
                $connectionString =
                    $this->dbType->value.
                    ':host='.
                    $this->host.
                    ';port='.
                    $this->port.
                    ';dbname='.
                    $this->databaseName;
                if (is_null($this->options)) {
                    $this->options = array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_STRINGIFY_FETCHES => false,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    );
                }
        }

        try {
            $this->connection = new PDO($connectionString, $this->username, $this->password, $this->options);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     *
     * @return ?PDO
     */
    public function getConnection(): ?PDO
    {
        return $this->connection;
    }

    public function getQuoteIdentifier(): string
    {
        $connection = $this->getConnection();
        if (is_null($connection)) {
            throw new RuntimeException('No connection available.');
        }
        switch ($connection->getAttribute(PDO::ATTR_DRIVER_NAME)) {
            case DatabaseType::PGSQL:
            case DatabaseType::SQLSRV:
            case DatabaseType::DBLIB:
            case DatabaseType::MSSQL:
            case DatabaseType::SYBASE:
            case DatabaseType::FIREBIRD:
                return '"';
            case DatabaseType::MYSQL:
            case DatabaseType::SQLITE:
            case DatabaseType::SQLITE2:
            case DatabaseType::SQLITE3:
            default:
                return '`';
        }
    }

    /**
     *
     * @return string
     */
    public function getLimitStyle(): string
    {
        $connection = $this->getConnection();
        if (is_null($connection)) {
            throw new RuntimeException('No connection available.');
        }
        switch ($connection->getAttribute(PDO::ATTR_DRIVER_NAME)) {
            case DatabaseType::SQLSRV:
            case DatabaseType::DBLIB:
            case DatabaseType::MSSQL:
                return self::LIMIT_STYLE_TOP_N;
            default:
                return self::LIMIT_STYLE_LIMIT;
        }
    }

    /**
     *
     * @return null|PDOStatement
     */
    public function getLastStatement(): ?PDOStatement
    {
        return $this->lastStatement;
    }

    /**
     *
     * @param string $query
     * @param array<string, mixed> $parameters
     * @return bool
     * @throws PDOException
     */
    public function execute(string $query, array $parameters = array()): bool
    {
        $connection = $this->getConnection();
        if (is_null($connection)) {
            throw new RuntimeException('No connection available.');
        }
        $statement = $connection->prepare($query);
        $this->lastStatement = $statement;
        foreach ($parameters as $key => &$param) {
            if (is_null($param)) {
                $type = PDO::PARAM_NULL;
            } elseif (is_bool($param)) {
                $type = PDO::PARAM_BOOL;
            } elseif (is_int($param)) {
                $type = PDO::PARAM_INT;
            } else {
                $type = PDO::PARAM_STR;
            }

            $statement->bindParam(/*is_int($key) ? ++$key :*/ $key, $param, $type);
        }

        $q = $statement->execute();
        return $q;
    }

    /**
     *
     * @param string $query
     * @param array<string, mixed> $parameters
     * @return array<mixed>
     * @throws PDOException
     */
    public function getRows(string $query, array $parameters = array()): array
    {
        $this->execute($query, $parameters);
        $statement = $this->getLastStatement();
        if (is_null($statement)) {
            throw new RuntimeException('No statement available.');
        }
        $rows = array();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     *
     * @param string $query
     * @param array<string, mixed> $parameters
     * @return ?PDOStatement
     * @throws PDOException
     */
    public function getExecutedStatement(string $query, array $parameters = array()): ?PDOStatement
    {
        $this->execute($query, $parameters);
        return $this->getLastStatement();
    }
}
