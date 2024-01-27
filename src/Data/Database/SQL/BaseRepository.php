<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 */

namespace zeroline\MiniLoom\Data\Database\SQL;

use PDOException;
use zeroline\MiniLoom\Data\Database\SQL\Connection as Connection;
use stdClass;

class BaseRepository
{
    const INTERNAL_FIELD_COUNT_RESULT = 'iCountResult';
    const INTERNAL_JOIN_PREFIX = 'jnd';

    const EMPTY_STRING = '';
    const EMPTY_SPACE = ' ';
    const IMPLODE_SEPARATOR = ',';
    const DOT = '.';
    const COLON = ':';
    const PARANTHESIS_OPEN = '(';
    const PARANTHESIS_CLOSE = ')';

    const KEYWORD_ASTERISK = '*';

    const KEYWORD_SELECT = 'SELECT';
    const KEYWORD_INSERT = 'INSERT';
    const KEYWORD_DELETE = 'DELETE';
    const KEYWORD_UPDATE = 'UPDATE';
    const KEYWORD_FROM = 'FROM';
    const KEYWORD_WHERE = 'WHERE';
    const KEYWORD_ORDER_BY = 'ORDER BY';
    const KEYWORD_LIMIT = 'LIMIT';
    const KEYWORD_OFFSET = 'OFFSET';
    const KEYWORD_IN = 'IN';
    const KEYWORD_NOT_IN = 'NOT IN';
    const KEYWORD_ASC = 'ASC';
    const KEYWORD_DESC = 'DESC';
    const KEYWORD_AND = 'AND';
    const KEYWORD_OR = 'OR';
    const KEYWORD_IS_NULL = 'IS NULL';
    const KEYWORD_IS_NOT_NULL = 'IS NOT NULL';
    const KEYWORD_LIKE = 'LIKE';
    const KEYWORD_JOIN = 'JOIN';
    const KEYWORD_ON = 'ON';
    const KEYWORD_SET = 'SET';
    const KEYWORD_VALUES = 'VALUES';
    const KEYWORD_INTO = 'INTO';
    const KEYWORD_OPERATOR_EQUAL = '=';
    const KEYWORD_OPERATOR_NOT_EQUAL = '<>';
    const KEYWORD_OPERATOR_GREATER_THAN = '>';
    const KEYWORD_OPERATOR_GREATER_THAN_OR_EQUAL = '>=';
    const KEYWORD_OPERATOR_LOWER_THAN = '<';
    const KEYWORD_OPERATOR_LOWER_THAN_OR_EQUAL = '<=';

    const ATTRIBUTE_KEY_NAME = 'name';
    const ATTRIBUTE_KEY_VALUE = 'value';
    const ATTRIBUTE_KEY_OPERATOR = 'operator';
    const ATTRIBUTE_KEY_DIRECTION = 'direction';
    const ATTRIBUTE_KEY_JOIN_TABLE_NAME = 'joinTableName';
    const ATTRIBUTE_KEY_JOIN_TABLE_FIELD_NAME = 'joinTableFieldName';
    const ATTRIBUTE_KEY_BASE_TABLE_FIELD_NAME = 'baseTableFieldName';

    /**
     *
     * @var null|int
     */
    protected ?int $limit = null;

    /**
     *
     * @var null|int
     */
    protected ?int $offset = null;

    /**
     *
     * @var array<(object{name: string, direction: string}&stdClass)>
     */
    protected array $order = array();

    /**
     *
     * @var array<(object{name: string, value: mixed, operator: string}&stdClass)>
     */
    protected array $where = array();

    /**
     *
     * @var array<string, mixed>
     */
    protected array $placeholder = array();

    /**
     *
     * @var string
     */
    protected string $table;

    /**
     *
     * @var array<string>
     */
    protected array $selectFields = array();

    /**
     *
     * @var array<int|string, array<string, string>|string>
     */
    protected array $joins = array();

    /**
     *
     * @var null|Connection
     */
    protected ?Connection $currentConnection = null;

    /**
     *
     * @return null|Connection
     */
    protected function getConnection(): ?Connection
    {
        if (is_null($this->currentConnection)) {
            $this->currentConnection = ConnectionManager::getDefaultConnection();
        }
        return $this->currentConnection;
    }

    /**
     *
     * @param string $connectionName
     * @return BaseRepository
     */
    public function switchConnection(string $connectionName): BaseRepository
    {
        $this->currentConnection = ConnectionManager::getConnection($connectionName);
        return $this;
    }

    public function connect() : void
    {
        $this->getConnection()?->connect();
    }

    /**
     *
     * @param string $tableName
     * @return BaseRepository
     */
    public function setTable(string $tableName): BaseRepository
    {
        $this->table = $tableName;
        return $this;
    }

    /**
     *
     * @return null|string
     */
    public function getTableName(): ?string
    {
        return $this->table;
    }

    /**
     *
     * @param string $fieldName
     * @return BaseRepository
     */
    public function selectField(string $fieldName): BaseRepository
    {
        $this->selectFields[] = $fieldName;
        return $this;
    }

    /************************************************************************/
    /* CHAIN FUNCTIONS - WHERE */
    /************************************************************************/

    /**
     *
     * @param string $fieldName
     * @param mixed $value
     * @param string $operator
     * @return BaseRepository
     */
    public function whereRaw(string $fieldName, $value, string $operator): BaseRepository
    {
        $this->where[] = (object) array(
            self::ATTRIBUTE_KEY_NAME => $fieldName,
            self::ATTRIBUTE_KEY_VALUE => $value,
            self::ATTRIBUTE_KEY_OPERATOR => $operator
        );
        return $this;
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     * @return BaseRepository
     */
    public function where(string $name, $value): BaseRepository
    {
        if (is_null($value)) {
            $this->whereNull($name);
        } else {
            $this->whereRaw($name, $value, self::KEYWORD_OPERATOR_EQUAL);
        }
        return $this;
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     * @return BaseRepository
     */
    public function whereNot(string $name, $value): BaseRepository
    {
        if (is_null($value)) {
            $this->whereNotNull($name);
        } else {
            $this->whereRaw($name, $value, self::KEYWORD_OPERATOR_NOT_EQUAL);
        }
        return $this;
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     * @return BaseRepository
     */
    public function whereLike(string $name, $value): BaseRepository
    {
        $this->whereRaw($name, $value, self::KEYWORD_LIKE);
        return $this;
    }

    /**
     *
     * @param string $name
     * @return BaseRepository
     */
    public function whereNull(string $name): BaseRepository
    {
        $this->whereRaw($name, null, self::KEYWORD_IS_NULL);
        return $this;
    }

    /**
     *
     * @param string $name
     * @return BaseRepository
     */
    public function whereNotNull(string $name): BaseRepository
    {
        $this->whereRaw($name, null, self::KEYWORD_IS_NOT_NULL);
        return $this;
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     * @return BaseRepository
     */
    public function whereGreaterThan(string $name, $value): BaseRepository
    {
        $this->whereRaw($name, $value, self::KEYWORD_OPERATOR_GREATER_THAN);
        return $this;
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     * @return BaseRepository
     */
    public function whereGreaterThanOrEqual(string $name, $value): BaseRepository
    {
        $this->whereRaw($name, $value, self::KEYWORD_OPERATOR_GREATER_THAN_OR_EQUAL);
        return $this;
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     * @return BaseRepository
     */
    public function whereLowerThan(string $name, $value): BaseRepository
    {
        $this->whereRaw($name, $value, self::KEYWORD_OPERATOR_LOWER_THAN);
        return $this;
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     * @return BaseRepository
     */
    public function whereLowerThanOrEqual(string $name, $value): BaseRepository
    {
        $this->whereRaw($name, $value, self::KEYWORD_OPERATOR_LOWER_THAN_OR_EQUAL);
        return $this;
    }

    /**
     *
     * @param string $name
     * @param array<mixed> $values
     * @return BaseRepository
     */
    public function whereIn(string $name, array $values): BaseRepository
    {
        $this->whereRaw($name, $values, self::KEYWORD_IN);
        return $this;
    }

    /**
     *
     * @param string $name
     * @param array<mixed> $values
     * @return BaseRepository
     */
    public function whereNotIn(string $name, array $values): BaseRepository
    {
        $this->whereRaw($name, $values, self::KEYWORD_NOT_IN);
        return $this;
    }

    /************************************************************************/
    /* CHAIN FUNCTIONS - ORDER */
    /************************************************************************/

    /**
     *
     * @param string $name
     * @param string $direction
     * @return BaseRepository
     */
    public function orderBy(string $name, string $direction): BaseRepository
    {
        $this->order[] = (object) array(
            self::ATTRIBUTE_KEY_NAME => $name,
            self::ATTRIBUTE_KEY_DIRECTION => $direction
        );
        return $this;
    }

    /**
     *
     * @param string $name
     * @return BaseRepository
     */
    public function orderByAsc(string $name): BaseRepository
    {
        $this->orderBy($name, self::KEYWORD_ASC);
        return $this;
    }

    /**
     *
     * @param string $name
     * @return BaseRepository
     */
    public function orderByDesc(string $name): BaseRepository
    {
        $this->orderBy($name, self::KEYWORD_DESC);
        return $this;
    }

    /************************************************************************/
    /* CHAIN FUNCTIONS - PAGING */
    /************************************************************************/

    /**
     *
     * @param int $limit
     * @return BaseRepository
     */
    public function limit(int $limit): BaseRepository
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     *
     * @param int $offset
     * @return BaseRepository
     */
    public function offset(int $offset): BaseRepository
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Add a join directive
     *
     * @param string $joiningTableName
     * @param string $joiningTableFieldName
     * @param string $baseTableFieldName
     * @return BaseRepository
     */
    public function join(string $joiningTableName, string $joiningTableFieldName, string $baseTableFieldName): BaseRepository
    {
        $this->joins[] = [
            self::ATTRIBUTE_KEY_JOIN_TABLE_NAME => $joiningTableName,
            self::ATTRIBUTE_KEY_JOIN_TABLE_FIELD_NAME => $joiningTableFieldName,
            self::ATTRIBUTE_KEY_BASE_TABLE_FIELD_NAME => $baseTableFieldName
        ];
        return $this;
    }

    /************************************************************************/
    /* BUILDER */
    /************************************************************************/

    /**
     * Use engines quotes
     * @param string $name
     * @return string
     */
    protected function encapsulate(string $name): string
    {
        return $this->getConnection()?->getQuoteIdentifier() . $name . $this->getConnection()?->getQuoteIdentifier();
    }

    /**
     * Generates placeholder name for values and uses standard quotes used in
     * the specified engine
     * @param string $name
     * @param mixed $value
     * @return string
     */
    protected function encapsulatePlaceholder(string $name, $value): string
    {
        $newName = self::COLON . $name;
        if (array_key_exists($newName, $this->placeholder)) {
            return $this->encapsulate($name . mt_rand());
        } else {
            $this->placeholder[$newName] = $value;
            return $newName;
        }
    }

    /**
     * Build full insert query string
     * @param array<string, mixed> $data
     * @return string
     */
    protected function buildInsert(array $data = array()): string
    {
        $insertStr = self::KEYWORD_INSERT.self::EMPTY_SPACE.self::KEYWORD_INTO.self::EMPTY_SPACE.$this->encapsulate($this->table).self::EMPTY_SPACE;
        $fieldsArr = array();
        $valueArr = array();
        foreach ($data as $k => $v) {
            $fieldsArr[] = $k;
            $valueArr[] = $this->encapsulatePlaceholder($k, $v);
        }

        $insertStr .= self::PARANTHESIS_OPEN . implode(self::IMPLODE_SEPARATOR, $fieldsArr) . self::PARANTHESIS_CLOSE.self::EMPTY_SPACE;
        $insertStr .= self::KEYWORD_VALUES.self::EMPTY_SPACE.self::PARANTHESIS_OPEN.implode(self::IMPLODE_SEPARATOR, $valueArr).self::PARANTHESIS_CLOSE.self::EMPTY_SPACE;
        return $insertStr;
    }

    /**
     * Build full select count(*) query string
     * @return string
     */
    protected function buildSelectCount(): string
    {
        $selectStr = 'SELECT COUNT(*) AS'.self::EMPTY_SPACE.self::INTERNAL_FIELD_COUNT_RESULT.self::EMPTY_SPACE;
        $selectStr .= self::KEYWORD_FROM.self::EMPTY_SPACE.$this->encapsulate($this->table).self::EMPTY_SPACE;
        $selectStr .= self::EMPTY_SPACE.$this->buildWhere();
        $selectStr .= self::EMPTY_SPACE.$this->buildOrderBy();
        if (!is_null($this->limit)) {
            $selectStr .= self::EMPTY_SPACE.self::KEYWORD_LIMIT.self::EMPTY_SPACE.$this->limit;
        }
        if (!is_null($this->limit) && !is_null($this->offset)) {
            $selectStr .= self::EMPTY_SPACE.self::KEYWORD_OFFSET.self::EMPTY_SPACE.$this->limit;
        }

        return $selectStr;
    }

    /**
     * Build join string
     * @return string
     */
    protected function buildJoin(): string
    {
        $joinString = self::EMPTY_STRING;
        if ($this->hasJoins()) {
            foreach ($this->joins as $joinIndex => $joinData) {
                if(is_array($joinData) && array_key_exists(self::ATTRIBUTE_KEY_JOIN_TABLE_NAME, $joinData) && array_key_exists(self::ATTRIBUTE_KEY_JOIN_TABLE_FIELD_NAME, $joinData) && array_key_exists(self::ATTRIBUTE_KEY_BASE_TABLE_FIELD_NAME, $joinData)) {
                    $joiningTableName = $joinData[self::ATTRIBUTE_KEY_JOIN_TABLE_NAME];
                    $joiningTableFieldName = $joinData[self::ATTRIBUTE_KEY_JOIN_TABLE_FIELD_NAME];
                    $baseTableFieldName = $joinData[self::ATTRIBUTE_KEY_BASE_TABLE_FIELD_NAME];
                    $tablePrefix = self::INTERNAL_JOIN_PREFIX . $joinIndex;
                    $joinString .= self::EMPTY_SPACE.self::KEYWORD_JOIN.self::EMPTY_SPACE.$this->encapsulate($joiningTableName).self::EMPTY_SPACE.$tablePrefix.self::EMPTY_SPACE;
                    $joinString .= self::KEYWORD_ON.self::EMPTY_SPACE.self::PARANTHESIS_OPEN.$this->encapsulate($tablePrefix . self::DOT).$joiningTableFieldName.self::KEYWORD_OPERATOR_EQUAL.$baseTableFieldName.self::PARANTHESIS_CLOSE.self::EMPTY_SPACE;
                }                
            }
        }

        return $joinString;
    }

    /**
     *
     * @return array<string>
     */
    protected function buildGeneralSelectFields(): array
    {
        $joinSelectFields = array(spl_object_hash($this) . self::DOT.self::KEYWORD_ASTERISK);
        if ($this->hasJoins()) {
            foreach ($this->joins as $joinIndex => $joinData) {
                $tablePrefix = self::INTERNAL_JOIN_PREFIX . $joinIndex;
                $joinSelectFields[] = $tablePrefix . self::DOT.self::KEYWORD_ASTERISK;
            }
        }

        return $joinSelectFields;
    }

    /**
     * Check if joins are set
     * @return bool
     */
    protected function hasJoins(): bool
    {
        return (count($this->joins) > 0);
    }

    /**
     * Build full select query string
     * @return string
     */
    protected function buildSelect(): string
    {
        $selectStr = self::KEYWORD_SELECT.self::EMPTY_SPACE;
        if (count($this->selectFields)) {
            $selectStr .= implode(self::IMPLODE_SEPARATOR.self::EMPTY_SPACE, $this->selectFields).self::EMPTY_SPACE;
        } elseif ($this->hasJoins()) {
            $selectStr .= implode(self::IMPLODE_SEPARATOR.self::EMPTY_SPACE, $this->buildGeneralSelectFields());
        } else {
            $selectStr .= self::KEYWORD_ASTERISK.self::EMPTY_SPACE;
        }
        $selectStr .= self::KEYWORD_FROM.self::EMPTY_SPACE.$this->encapsulate($this->table).self::EMPTY_SPACE;
        // Join
        $selectStr .= self::EMPTY_SPACE.$this->buildJoin();
        // Where
        $selectStr .= self::EMPTY_SPACE.$this->buildWhere();
        // Order
        $selectStr .= self::EMPTY_SPACE.$this->buildOrderBy();
        if (!is_null($this->limit)) {
            $selectStr .= self::EMPTY_SPACE.self::KEYWORD_LIMIT.self::EMPTY_SPACE.$this->limit;
        }
        if (!is_null($this->limit) && !is_null($this->offset)) {
            $selectStr .= self::EMPTY_SPACE.self::KEYWORD_OFFSET.self::EMPTY_SPACE.$this->offset;
        }

        return $selectStr;
    }

    /**
     * Build full update query string
     * @param array<string, mixed> $data
     * @return string
     */
    protected function buildUpdate(array $data = array()): string
    {
        $updateStr = self::KEYWORD_UPDATE.self::EMPTY_SPACE.$this->encapsulate($this->table).self::KEYWORD_SET.self::EMPTY_SPACE;
        $updateArr = array();
        foreach ($data as $k => $v) {
            $updateArr[] = $k.self::EMPTY_SPACE .self::KEYWORD_OPERATOR_EQUAL.self::EMPTY_SPACE.$this->encapsulatePlaceholder($k, $v);
        }
        $updateStr .= implode(self::IMPLODE_SEPARATOR, $updateArr) . self::EMPTY_SPACE;
        $updateStr .= $this->buildWhere() . self::EMPTY_SPACE;
        return $updateStr;
    }

    /**
     * Build full delete query string
     * @return string
     */
    protected function buildDelete(): string
    {
        $deleteStr = self::KEYWORD_DELETE.self::EMPTY_SPACE.self::KEYWORD_FROM.self::EMPTY_SPACE.$this->encapsulate($this->table).self::EMPTY_SPACE;
        $deleteStr .= $this->buildWhere().self::EMPTY_SPACE;
        return $deleteStr;
    }

    /**
     * Build where string. Use $combineWith to switch between OR and AND.
     * @param string $combineWith
     * @return string
     */
    protected function buildWhere(string $combineWith = self::EMPTY_SPACE.self::KEYWORD_AND.self::EMPTY_SPACE): string
    {
        $whereStr = self::KEYWORD_WHERE.self::EMPTY_SPACE;
        $whereArr = array();
        foreach ($this->where as $where) {
            switch ($where->operator) {
                case self::KEYWORD_IN:
                case self::KEYWORD_NOT_IN:
                    if (count($where->value) > 0) {
                        $tmpStr = $this->encapsulate($where->name) . self::EMPTY_SPACE . $where->operator .self::EMPTY_SPACE.self::PARANTHESIS_OPEN;
                        $tmpArr = array();
                        for ($i = 0; $i < count($where->value); $i++) {
                            $v = $where->value[$i];
                            $p = $where->name . $i;
                            $tmpArr[] = $this->encapsulatePlaceholder($p, $v);
                        }
                        $tmpStr .= implode(self::IMPLODE_SEPARATOR, $tmpArr);
                        $tmpStr .= self::PARANTHESIS_CLOSE;
                        $whereArr[] = $tmpStr;
                    }

                    break;
                case self::KEYWORD_IS_NULL:
                case self::KEYWORD_IS_NOT_NULL:
                    $whereArr[] = $this->encapsulate($where->name) . self::EMPTY_SPACE . $where->operator;
                    break;
                default:
                    $whereArr[] = $this->encapsulate($where->name) . self::EMPTY_SPACE . $where->operator . self::EMPTY_SPACE . $this->encapsulatePlaceholder($where->name, $where->value);
                    break;
            }
        }

        $whereStr .= implode($combineWith, $whereArr);
        if ($whereStr == self::KEYWORD_WHERE.self::EMPTY_SPACE) {
            return self::EMPTY_STRING;
        }

        return $whereStr;
    }

    /**
     * Build order by string
     * @return string
     */
    protected function buildOrderBy(): string
    {
        $orderByStr = self::KEYWORD_ORDER_BY.self::EMPTY_SPACE;
        $orderByArr = array();
        foreach ($this->order as $order) {
            $orderByArr[] = $order->name.self::EMPTY_SPACE.$order->direction;
        }

        $orderByStr .= implode(self::IMPLODE_SEPARATOR, $orderByArr);
        if ($orderByStr == self::KEYWORD_ORDER_BY.self::EMPTY_SPACE) {
            return self::EMPTY_STRING;
        }

        return $orderByStr;
    }

    /************************************************************************/
    /* PERFORMER */
    /************************************************************************/

    /**
     * Clears all conditions
     */
    public function clearConditions() : void
    {
        $this->limit = null;
        $this->offset = null;
        $this->order = array();
        $this->placeholder = array();
        $this->selectFields = array();
        //$this->table = '';
        $this->where = array();
        $this->joins = array();
    }

    /**
     * Performs an insert query
     * @param array<string, mixed> $data
     * @return string|bool
     * @throws PDOException
     */
    public function create(array $data = array()) : string|bool
    {
        $query = $this->buildInsert($data);
        $result = false;
        if ($this->getConnection()->execute($query, $this->placeholder)) {
            $result = $this->getConnection()->getConnection()->lastInsertId();
        }
        $this->clearConditions();
        return $result;
    }

    /**
     * Performs a count query
     * @return null|int
     * @throws PDOException
     */
    public function count(): ?int
    {
        $query = $this->buildSelectCount();
        $result = $this->getConnection()->getRows($query, $this->placeholder);
        $this->clearConditions();
        if ($result && isset($result[0]) && isset($result[0][self::INTERNAL_FIELD_COUNT_RESULT])) {
            return intval($result[0][self::INTERNAL_FIELD_COUNT_RESULT]);
        }

        return null;
    }

    /**
     * Perfoms a select statement with the previous given parameters
     * Returns raw rows
     * @return array<mixed>
     */
    public function read(): array
    {
        $result = array();

        $query = $this->buildSelect();
        $result = $this->getConnection()->getRows($query, $this->placeholder);
        $this->clearConditions();

        return $result;
    }

    /**
     * Same as @see read or @see readModels but returns only one row
     * @return mixed
     */
    public function readOne() : mixed
    {
        $result = null;
        $rows = array();
        $this->limit(1);
        $rows = $this->read();

        if (isset($rows[0])) {
            return $rows[0];
        }

        return $result;
    }

    /**
     * Performs a raw query and returns rows
     * @param string $query
     * @return array<mixed>
     */
    public function readRaw(string $query): array
    {
        $rows = $this->getConnection()->getRows($query, array());
        return $rows;
    }

    /**
     * Performs an update query
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(array $data = array()): bool
    {
        $query = $this->buildUpdate($data);
        $result =  $this->getConnection()->execute($query, $this->placeholder);
        $this->clearConditions();
        return $result;
    }

    /**
     * Performs a delete query
     * @return bool
     */
    public function delete(): bool
    {
        $query = $this->buildDelete();
        $result = $this->getConnection()->execute($query, $this->placeholder);
        $this->clearConditions();
        return $result;
    }

    /**
     * Executes the given raw statement
     *
     * @param string $sqlStatement
     * @return boolean
     */
    public function executeRaw(string $sqlStatement): bool
    {
        return $this->getConnection()->execute($sqlStatement);
    }

    /************************************************************************/
    /* TRANSACTION */
    /************************************************************************/

    /**
     * Start a transaction
     *
     * @return boolean
     */
    public function beginTransaction(): bool
    {
        return $this->getConnection()->getConnection()->beginTransaction();
    }

    /**
     * Commit the current transaction changes
     *
     * @return boolean
     */
    public function commitTransaction(): bool
    {
        return $this->getConnection()->getConnection()->commit();
    }

    /**
     * Rollback the current transaction changes
     *
     * @return boolean
     */
    public function rollbackTransaction(): bool
    {
        return $this->getConnection()->getConnection()->rollBack();
    }
}
