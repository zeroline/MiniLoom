<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 * The DataContainer class provides a simple way to store data.
 */

namespace zeroline\MiniLoom\Data;

use JsonSerializable;
use AllowDynamicProperties;

#[AllowDynamicProperties]
class DataContainer implements JsonSerializable
{
    /**
     *
     * @var array<string, mixed>
     */
    private array $data = array();

    /**
     *
     * @param array<string, mixed>|object $data
     */
    public function __construct( array|object $data = array())
    {
        if (!is_array($data)) {
            $data = (array)$data;
        }
        $this->data = $data;
    }

    /**
     *
     * @return array<string, mixed>
     */
    public function getData() : array
    {
        return $this->data;
    }

    /**
     * Return stored data if found else
     * return given fallback value
     *
     * @param string $name
     * @param mixed $fallback
     * @return mixed|null
     */
    public function get(string $name, mixed $fallback = null) : mixed
    {
        if (is_null($this->{$name})) {
            return $fallback;
        }
        return $this->{$name};
    }

    /**
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
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
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     *
     * @return mixed
     */
    public function jsonSerialize() : mixed
    {
        return $this->getData();
    }
}
