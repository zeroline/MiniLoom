<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Helper
 * 
 * The ArrayReader helps to retrieve values from an array by a given key. Therefore a deep array can be traversed by
 * using a dot notation. If the key does not exist, a fallback value is returned. If the fallback value is a callable,
 */

namespace zeroline\MiniLoom\Helper;

class ArrayReader
{
    /**
     * Returns the value of the given key in the given array. If the key does not exist, the fallback value is returned.
     * If the fallback value is a callable, it is called and the return value is returned.
     * 
     * @param string $key
     * @param mixed $fallback
     * @return mixed
     */
    public static function get(array $config, string $key, mixed $fallback = null) : mixed
    {
        return static::parseArrayPath($key, $config) ?: (is_callable($fallback) ? call_user_func($fallback) : $fallback );
    }

    /**
     * Wrapper for the get method. Used for backwards compatibility.
     * 
     * @obsolete
     * @param array $config 
     * @param string $key 
     * @param mixed $fallback 
     * @return mixed 
     */
    public static function getConfig(array $config, string $key, mixed $fallback = null) : mixed
    {
        return static::get($config, $key, $fallback);
    }

    /**
     * Returns the value of the given key in the given array. If the key does not exist, the fallback value is returned.
     * If the fallback value is a callable, it is called and the return value is returned.
     *
     * @param string $key
     * @param array $array
     * @return mixed
     */
    private static function parseArrayPath(string $key, array $array, string $seperator = '.') : mixed
    {
        $parts = explode($seperator, $key);
        if (sizeof($parts) === 1) {
            if (array_key_exists($parts[0], $array)) {
                return $array[$parts[0]];
            }
        } else {
            if (array_key_exists($parts[0], $array)) {
                return static::parseArrayPath(implode($seperator, array_slice($parts, 1)), $array[$parts[0]]);
            }
        }
        return null;
    }
}
