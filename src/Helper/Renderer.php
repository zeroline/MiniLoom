<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Helper
 *
 * The Renderer class is a simple helper class to render files or html strings.
 */

namespace zeroline\MiniLoom\Helper;

final class Renderer
{
    /**
     *
     * @param string $filename
     * @param array<mixed> $data
     * @return string
     */
    public static function renderFile(string $filename, array $data = array()) : string
    {
        if (extension_loaded('gzip')) {
            ob_start("ob_gzhandler");
        } else {
            ob_start();
        }

        extract($data);
        require $filename;
        $result = ob_get_clean();
        if ($result !== false) {
            return $result;
        }
        return '';
    }

    /**
     *
     * @param string $html
     * @param array<mixed> $data
     * @return string
     */
    public static function renderHtml(string $html, array $data = array()) : string
    {
        if (extension_loaded('gzip')) {
            ob_start("ob_gzhandler");
        } else {
            ob_start();
        }

        extract($data);
        eval('?> ' . $html . ' ');
        $result = ob_get_clean();
        if ($result !== false) {
            return $result;
        }
        return '';
    }

    /**
     * Simple & quick text renderer.
     *
     * @param string $message
     * @param array<string, mixed> $context
     * @param string $prefix
     * @param string $suffix
     * @return ?string
     */
    public static function interpolate(string $message, array $context = array(), $prefix = '\{\{\s*', $suffix = '\s*\}\}'): ?string
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
        // check that the value can be casted to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, "__toString"))) {
                $replace[$prefix . $key . $suffix] = (string)$val;
            }
        }

        // use regex to replace values
        $message = preg_replace(array_map(function ($key) {
            return '/' . $key . '/';
        }, array_keys($replace)), array_values($replace), $message);

        return $message;
    }
}
