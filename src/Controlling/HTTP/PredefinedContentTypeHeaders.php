<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Controlling
 *
 */

namespace zeroline\MiniLoom\Controlling\HTTP;

class PredefinedContentTypeHeaders
{
    public const HTML = 'text/html';
    public const JSON = 'application/json';
    public const TEXT_PLAIN = 'text/plain';
    public const XML = 'application/xml';

    public static function setHTMLHeader() : void
    {
        header('Content-Type: ' . self::HTML, true);
    }

    public static function setJSONHeader() : void
    {
        header('Content-Type: ' . self::JSON, true);
    }

    public static function setPlainTextHeader() : void
    {
        header('Content-Type: ' . self::TEXT_PLAIN, true);
    }

    public static function setXMLHeader() : void
    {
        header('Content-Type: ' . self::XML, true);
    }

    public static function setJWTPlainHeader() : void
    {
        header('Content-Type: ' . self::TEXT_PLAIN, true);
        header("Content-Transfer-Encoding: base64", true);
    }
}
