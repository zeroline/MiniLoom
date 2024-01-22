<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage ObjectHandling
 *
 * The SingletonTrait provides a singleton pattern for classes.
 */

namespace zeroline\MiniLoom\ObjectHandling;

use ReflectionException;
use zeroline\MiniLoom\ObjectHandling\ObjectFactory as ObjectFactory;

trait SingletonTrait
{
    /**
     * Store singleton instances
     * @var array
     */
    public static $instances = array();

    /**
     * Get the singleton instance
     * @return mixed
     * @throws ReflectionException
     */
    public static function getInstance() : mixed
    {
        $c = get_called_class();
        if (!array_key_exists($c, static::$instances)) {
            static::$instances[$c] = ObjectFactory::create($c, func_get_args());
        }
        return static::$instances[$c];
    }

    /**
     * Prevent cloning of the instance
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Prevent unserializing of the instance
     * @return void
     */
    public function __wakeup()
    {
    }
}
