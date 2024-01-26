<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Helper
 *
 * The EmbedImageHelper class is a simple helper class to render files or html strings.
 */

namespace zeroline\MiniLoom\Helper;

use Exception;

final class EmbedImageHelper
{
    /**
     * Generates the image source string to embedd images.
     * If no mime type is given, the mime type will be detected automatically.
     * @example list($base64content, $mimeType) = EmbedImageHelper::generateBase64ImageDataAndMimeFromFile($filename);
     * @param string $filename
     * @param null|string $mime
     * @return array<string>
     */
    public static function generateBase64ImageDataAndMimeFromFile(string $filename, ?string $mime = null) : array
    {
        $contents = file_get_contents($filename);
        $base64 = base64_encode($contents);
        if (is_null($mime)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $filename);
            if($mime === false) {
                throw new Exception("Could not detect mime type of file: " . $filename);
            }
            finfo_close($finfo);
        }

        return [$base64, $mime];
    }

    /**
     * Generates the image source string to embedd images.
     * @param string $base64data
     * @param string $mimeType
     * @return string
     */
    public static function generateImageSourceStringFromData(string $base64data, string $mimeType) : string
    {
        return "data:" . $mimeType . ";base64," . $base64data;
    }

    /**
     * Generates the image source string to embedd images.
     * If no mime type is given, the mime type will be detected automatically.
     * Use this in the img tag: <img src="<?php echo EmbedImageHelper::generateImageSourceStringFromFile($filename); ?>" />
     * @param string $filename
     * @param null|string $mime
     * @return string
     */
    public static function generateImageSourceStringFromFile(string $filename, ?string $mime = null) : string
    {
        list($base64data, $mimeType) = static::generateBase64ImageDataAndMimeFromFile($filename, $mime);
        return static::generateImageSourceStringFromData($base64data, $mimeType);
    }
}
