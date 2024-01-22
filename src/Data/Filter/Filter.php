<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 * 
 * The Validator class provides a set of static methods to validate data.
 */

namespace zeroline\MiniLoom\Data\Filter;

final class Filter {
    /*****************************************************/
    /** HTML STRING FILTER */
    /*****************************************************/   

    /**
     * Encodes the given (HTML) string using @see htmlspecialchars
     *
     * @param string $value
     * @return string
     */
    public static function filterEncodeHtml(string $value): string
    {
        return htmlspecialchars($value, ENT_COMPAT | ENT_HTML5, 'UTF-8', false);
    }

    /**
     * Encodes the given (HTML) string using @strip_tags
     *
     * @param string $value
     * @param array $allowable_tags
     * @return string
     */
    public static function filterStripHtml(string $value, array $allowable_tags = array()): string
    {
        return strip_tags($value, $allowable_tags);
    }

    /*****************************************************/
    /** CUSTOM FILTER */
    /*****************************************************/  
    public static function filterCustom(string $value, callable $callback): string
    {
        return $callback($value);
    }
}