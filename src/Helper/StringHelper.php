<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Helper
 *
 * The StringHelper class provides some useful string functions.
 * Useful only in PHP environments before 8
 * @see https://www.php.net/manual/en/function.str-ends-with.php
 * @see https://www.php.net/manual/en/function.str-starts-with.php
 * @see https://www.php.net/manual/en/function.str-contains.php
 */

namespace zeroline\MiniLoom\Helper;

final class StringHelper
{
    /**
     * Checks if a string starts with a given substring.
     * @obsolete Use PHP 8 function instead.
     * @see https://www.php.net/manual/en/function.str-starts-with.php
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public static function startsWith(string $haystack, string $needle) : bool
    {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    /**
     * Checks if a string ends with a given substring.
     * @obsolete Use PHP 8 function instead.
     * @see https://www.php.net/manual/en/function.str-ends-with.php
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public static function endsWith(string $haystack, string $needle) : bool
    {
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    /**
     * Checks if a string contains a given substring.
     * @obsolete Use PHP 8 function instead.
     * @see https://www.php.net/manual/en/function.str-contains.php
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public static function contains(string $haystack, string $needle) : bool
    {
        return strpos($haystack, $needle) !== false;
    }
}
