<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Helper
 *
 * The TempFileHelper class provides some useful functions to create temporary files.
 */

namespace zeroline\MiniLoom\Helper;

use Exception;


final class TempFileHelper
{
    /**
     * Writes a string to a temporary file.
     * @param string $data
     * @param string $prefix
     * @return null|string
     */
    public static function write(string $data, string $prefix = '') : ?string
    {
        $temp_file = tempnam(sys_get_temp_dir(), $prefix);
        if ($temp_file === false) {
            throw new Exception('Could not create temporary file');
        }

        if (file_put_contents($temp_file, $data)) {
            return $temp_file;
        }
        return null;
    }

    /**
     * Reads a temporary file.
     * @param string $filename
     * @return string|bool
     */
    public static function read(string $filename) : string|bool
    {
        return file_get_contents($filename);
    }
}
