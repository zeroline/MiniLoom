<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 */

namespace zeroline\MiniLoom\Data\Database\SQL;

use zeroline\MiniLoom\Data\Database\SQL\BaseRepository as BaseRepository;
use RuntimeException;

class ModelRepository extends BaseRepository
{
    protected string $modelClassName = '';

    /**
     *
     * @param string $name
     */
    public function setModelClassName(string $name) : void
    {
        $this->modelClassName = $name;
    }

    /**
     *
     * @return string
     */
    public function getModelClassName(): string
    {
        return $this->modelClassName;
    }

    /**
     *
     * @return bool
     */
    public function isModelClassNameSpecified(): bool
    {
        return (isset($this->modelClassName) && !empty($this->modelClassName));
    }

    /************************************************************************/
    /* PERFORMER */
    /************************************************************************/

    /**
     * Perfoms a select statement with the previous given parameters
     * Returns raw rows
     * @return array<mixed>
     */
    public function read(): array
    {
        $result = array();
        if ($this->isModelClassNameSpecified()) {
            $result = $this->readModels($this->getModelClassName());
        } else {
            $result = parent::read();
        }

        $this->clearConditions();

        return $result;
    }

    /**
     * Same as @see read but returns model objects of the given class
     * @param string $modelClass
     * @return array<mixed>
     */
    public function readModels(string $modelClass): array
    {
        $query = $this->buildSelect();
        $rows = $this->getConnection()->getRows($query, $this->placeholder);
        $models = array();
        foreach ($rows as $row) {
            $models[] = new $modelClass((is_array($row) ? $row : (array) $row));
        }
        $this->clearConditions();
        return $models;
    }

    /**
     * Same as @see read or @see readModels but returns only one row/instance
     * @return mixed
     */
    public function readOne() : mixed
    {
        $result = null;
        $rows = array();
        $this->limit(1);

        if ($this->isModelClassNameSpecified()) {
            $rows = $this->readModels($this->getModelClassName());
        } else {
            $rows = parent::readOne();
        }

        if (isset($rows[0])) {
            return $rows[0];
        }

        return $result;
    }

    /**
     * Performs a raw query and returns rows or instances
     * @param string $query
     * @return array<mixed>
     */
    public function readRaw(string $query): array
    {
        $rows = $this->getConnection()->getRows($query, array());
        if ($this->isModelClassNameSpecified()) {
            $models = array();
            $modelClass = $this->getModelClassName();
            foreach ($rows as $row) {
                $models[] = new $modelClass((is_array($row) ? $row : (array) $row));
            }
            return $models;
        } else {
            return $rows;
        }
    }
}
