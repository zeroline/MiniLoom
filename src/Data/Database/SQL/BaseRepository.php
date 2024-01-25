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

class BaseRepository
{
    const INTERNAL_FIELD_COUNT_RESULT = 'iCountResult';
    const INTERNAL_JOIN_PREFIX = 'jnd';

    protected ?int $limit = null;
    protected ?int $offset = null;
    protected array $order = array();
    protected array $where = array();
    protected array $placeholder = array();
    protected ?string $table = null;
    protected array $selectFields = array();
    protected array $joins = array();
    protected ?Connection $currentConnection = null;

    /**
     * 
     * @return null|Connection 
     */
    protected function getConnection(): ?Connection
    {
        if(is_null($this->currentConnection)) {
            $this->currentConnection = ConnectionManager::getDefaultConnection();
        }
        return $this->currentConnection;
    }

    /**
     * 
     * @param string $connectionName 
     * @return BaseRepository 
     */
    public function setConnection(string $connectionName): BaseRepository
    {
        $this->currentConnection = ConnectionManager::getConnection($connectionName);
        return $this;
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
     * @return string 
     */
    public function getTableName(): string
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
            'name' => $fieldName,
            'value' => $value,
            'operator' => $operator
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
            $this->whereRaw($name, $value, '=');
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
            $this->whereRaw($name, $value, '<>');
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
        $this->whereRaw($name, $value, 'LIKE');
        return $this;
    }

    /**
     * 
     * @param string $name 
     * @return BaseRepository 
     */
    public function whereNull(string $name): BaseRepository
    {
        $this->whereRaw($name, null, 'IS NULL');
        return $this;
    }

    /**
     * 
     * @param string $name 
     * @return BaseRepository 
     */
    public function whereNotNull(string $name): BaseRepository
    {
        $this->whereRaw($name, null, 'IS NOT NULL');
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
        $this->whereRaw($name, $value, '>');
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
        $this->whereRaw($name, $value, '>=');
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
        $this->whereRaw($name, $value, '<');
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
        $this->whereRaw($name, $value, '<=');
        return $this;
    }

    /**
     * 
     * @param string $name 
     * @param array $values 
     * @return BaseRepository 
     */
    public function whereIn(string $name, array $values): BaseRepository
    {
        $this->whereRaw($name, $values, 'IN');
        return $this;
    }

    /**
     * 
     * @param string $name 
     * @param array $values 
     * @return BaseRepository 
     */
    public function whereNotIn(string $name, array $values): BaseRepository
    {
        $this->whereRaw($name, $values, 'NOT IN');
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
            'name' => $name,
            'direction' => $direction
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
        $this->orderBy($name, 'ASC');
        return $this;
    }

    /**
     * 
     * @param string $name 
     * @return BaseRepository 
     */
    public function orderByDesc(string $name): BaseRepository
    {
        $this->orderBy($name, 'DESC');
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
            'joinTableName' => $joiningTableName,
            'joinTableFieldName' => $joiningTableFieldName,
            'baseTableFieldName' => $baseTableFieldName
        ];
        return $this;
    }

    /*
    public function join(string $modelClass, string $joiningTableName, string $joiningTableFieldName, string $baseTableFieldName): BaseRepository
    {
        $this->joins[] = [
            'modelClass' => $modelClass,
            'joinTableName' => $joiningTableName,
            'joinTableFieldName' => $joiningTableFieldName,
            'baseTableFieldName' => $baseTableFieldName
        ];
        return $this;
    }
    */

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
        return $this->getConnection()->getQuoteIdentifier() . $name . $this->getConnection()->getQuoteIdentifier();
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
        $newName = ':' . $name;
        if (array_key_exists($newName, $this->placeholder)) {
            return $this->encapsulate($name . mt_rand(), $value);
        } else {
            $this->placeholder[$newName] = $value;
            return $newName;
        }
    }

    /**
     * Build full insert query string
     * @param array $data
     * @return string
     */
    protected function buildInsert(array $data = array()): string
    {
        $insertStr = 'INSERT INTO ' . $this->encapsulate($this->table) . ' ';
        $fieldsArr = array();
        $valueArr = array();
        foreach ($data as $k => $v) {
            $fieldsArr[] = $k;
            $valueArr[] = $this->encapsulatePlaceholder($k, $v);
        }

        $insertStr .= '(' . implode(',', $fieldsArr) . ') ';
        $insertStr .= 'VALUES (' . implode(',', $valueArr) . ') ';
        return $insertStr;
    }

    /**
     * Build full select count(*) query string
     * @return string
     */
    protected function buildSelectCount(): string
    {
        $selectStr = 'SELECT COUNT(*) AS ' . self::INTERNAL_FIELD_COUNT_RESULT . ' ';
        $selectStr .= 'FROM ' . $this->encapsulate($this->table) . ' ';
        $selectStr .= ' ' . $this->buildWhere();
        $selectStr .= ' ' . $this->buildOrderBy();
        if (!is_null($this->limit)) {
            $selectStr .= ' LIMIT ' . $this->limit;
        }
        if (!is_null($this->limit) && !is_null($this->offset)) {
            $selectStr .= ' OFFSET ' . $this->limit;
        }

        return $selectStr;
    }

    /**
     * Build join string
     * @return string 
     */
    protected function buildJoin(): string
    {
        $joinString = '';
        if ($this->hasJoins()) {
            foreach ($this->joins as $joinIndex => $joinData) {
                $joiningTableName = $joinData['joinTableName'];
                $joiningTableFieldName = $joinData['joiningTableFieldName'];
                $baseTableFieldName = $joinData['baseTableFieldName'];
                $tablePrefix = self::INTERNAL_JOIN_PREFIX . $joinIndex;
                $joinString .= ' JOIN ' . $this->encapsulate($joiningTableName) . ' ' . $tablePrefix . ' ';
                $joinString .= 'ON (' . $this->encapsulate($tablePrefix . '.') . $joiningTableFieldName . '=' . $baseTableFieldName . ')';
            }
        }

        return $joinString;
    }

    /**
     *
     * @return array
     */
    protected function buildGeneralSelectFields(): array
    {
        $joinSelectFields = array(spl_object_hash($this) . '.*');
        if ($this->hasJoins()) {
            foreach ($this->joins as $joinIndex => $joinData) {
                $tablePrefix = self::INTERNAL_JOIN_PREFIX . $joinIndex;
                $joinSelectFields[] = $tablePrefix . '.*';
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
        $selectStr = 'SELECT ';
        if (count($this->selectFields)) {
            $selectStr .= implode(', ', $this->selectFields) . ' ';
        } elseif ($this->hasJoins()) {
            $selectStr .= $this->buildGeneralSelectFields();
        } else { 
            $selectStr .= '* ';
        }
        $selectStr .= 'FROM ' . $this->encapsulate($this->table) . ' ';
        // Join
        $selectStr .= ' ' . $this->buildJoin();
        // Where
        $selectStr .= ' ' . $this->buildWhere();
        // Order
        $selectStr .= ' ' . $this->buildOrderBy();
        if (!is_null($this->limit)) {
            $selectStr .= ' LIMIT ' . $this->limit;
        }
        if (!is_null($this->limit) && !is_null($this->offset)) {
            $selectStr .= ' OFFSET ' . $this->offset;
        }

        return $selectStr;
    }

    /**
     * Build full update query string
     * @param array $data
     * @return string
     */
    protected function buildUpdate(array $data = array()): string
    {
        $updateStr = 'UPDATE ' . $this->encapsulate($this->table) . ' SET ';
        $updateArr = array();
        foreach ($data as $k => $v) {
            $updateArr[] = $k . ' = ' . $this->encapsulatePlaceholder($k, $v);
        }
        $updateStr .= implode(',', $updateArr) . ' ';
        $updateStr .= $this->buildWhere() . ' ';
        return $updateStr;
    }

    /**
     * Build full delete query string
     * @return string
     */
    protected function buildDelete(): string
    {
        $deleteStr = 'DELETE FROM ' . $this->encapsulate($this->table) . ' ';
        $deleteStr .= $this->buildWhere() . ' ';
        return $deleteStr;
    }

    /**
     * Build where string. Use $combineWith to switch between OR and AND.
     * @param string $combineWith
     * @return string
     */
    protected function buildWhere(string $combineWith = ' AND '): string
    {
        $whereStr = 'WHERE ';
        $whereArr = array();
        foreach ($this->where as $where) {
            switch ($where->operator) {
                case 'IN':
                case 'NOT IN':
                    if (count($where->value) > 0) {
                        $tmpStr = $this->encapsulate($where->name) . ' ' . $where->operator . ' (';
                        $tmpArr = array();
                        for ($i = 0; $i < count($where->value); $i++) {
                            $v = $where->value[$i];
                            $p = $where->name . $i;
                            $tmpArr[] = $this->encapsulatePlaceholder($p, $v);
                        }
                        $tmpStr .= implode(',', $tmpArr);
                        $tmpStr .= ')';
                        $whereArr[] = $tmpStr;
                    }

                    break;
                case 'IS NULL':
                case 'IS NOT NULL':
                    $whereArr[] = $this->encapsulate($where->name) . ' ' . $where->operator;

                    break;
                default:
                    $whereArr[] = $this->encapsulate($where->name) . ' ' . $where->operator . ' ' . $this->encapsulatePlaceholder($where->name, $where->value);

                    break;
            }
        }

        $whereStr .= implode($combineWith, $whereArr);
        if ($whereStr == 'WHERE ') {
            return '';
        }

        return $whereStr;
    }

    /**
     * Build order by string
     * @return string
     */
    protected function buildOrderBy(): string
    {
        $orderByStr = 'ORDER BY ';
        $orderByArr = array();
        foreach ($this->order as $order) {
            $orderByArr[] = $order->name . ' ' . $order->direction;
        }

        $orderByStr .= implode(',', $orderByArr);
        if ($orderByStr == 'ORDER BY ') {
            return '';
        }

        return $orderByStr;
    }
    
    /************************************************************************/
    /* PERFORMER */
    /************************************************************************/

    /**
     * Clears all conditions
     */
    public function clearConditions()
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
     * @param array $data 
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
     * @return array
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
     * @return array
     */
    public function readRaw(string $query): array
    {
        $rows = $this->getConnection()->getRows($query, array());
        return $rows;
    }

    /**
     * Performs an update query
     * @param array $data
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
