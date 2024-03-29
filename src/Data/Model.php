<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 * The Model class provides a simple way to store data.
 * It is different to the DataContainer class because it
 * provides a way to track changes to the data.
 * It also provides a way to define which fields should be
 * serialized and which fields are already there to avoid adding
 * unwanted fields to the data.
 */

namespace zeroline\MiniLoom\Data;

use JsonSerializable;
use AllowDynamicProperties;

#[AllowDynamicProperties]
class Model implements JsonSerializable
{
    /**
     *
     * @var array<string, mixed>
     */
    protected array $data = array();

    /**
     *
     * @var array<string, mixed>
     */
    protected array $dirtyFields = array();

    /**
     *
     * @var array<string>
     */
    protected array $serializableFields = array();

    /**
     *
     * @param array<string, mixed>|object $data
     */
    public function __construct(array|object $data = array())
    {
        if (!is_array($data)) {
            $data = (array)$data;
        }
        $this->data = $data;
    }

    /**
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name) : mixed
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return null;
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, mixed $value) : void
    {
        if (!isset($this->data[$name]) || $this->data[$name] != $value) {
            $this->dirtyFields[$name] = $value;
        }
        $this->data[$name] = $value;
    }

    /**
     *
     * @param string $name
     * @return bool
     */
    public function __isset(string $name) : bool
    {
        return isset($this->data[$name]);
    }

    /**
     *
     * @return array<string, mixed>
     */
    public function getDirtyFields(): array
    {
        return $this->dirtyFields;
    }

    /**
     *
     * @return array<string>
     */
    public function getDirtyFieldNames(): array
    {
        return array_keys($this->dirtyFields);
    }

    /**
     *
     * @return bool
     */
    public function isDirty(): bool
    {
        return (bool)(count($this->dirtyFields) > 0);
    }

    /**
     *
     */
    public function clearDirtyFields() : void
    {
        $this->dirtyFields = array();
    }

    /**
     *
     * @return array<string>
     */
    public function getExistingFieldNames(): array
    {
        return array_keys($this->data);
    }

    /**
     *
     * @param string $fieldName
     * @return bool
     */
    public function hasExistingField(string $fieldName): bool
    {
        return in_array($fieldName, $this->getExistingFieldNames());
    }

    /**
     *
     * @return mixed
     */
    public function jsonSerialize() : mixed
    {
        if (count($this->serializableFields) > 0) {
            $arr = array();
            foreach ($this->serializableFields as $field) {
                $arr[$field] = $this->{$field};
            }
            return $arr;
        } else {
            return $this->data;
        }
    }
}
