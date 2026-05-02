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

    public const TRANSFER_BASE64 = 'base64';

    private const HEADER_KEY_CONTENT_TYPE = 'Content-Type';
    private const HEADER_KEY_CONTENT_TRANSFER = 'Content-Transfer-Encoding';
    private const HEADER_DECLARATOR = ': ';

    /**
     * Internal helper function to set a header
     *
     * @param string $header
     * @param string $value
     * @param bool $replace
     * @return void
     */
    protected static function setCustomHeader(string $header, string $value, bool $replace = true): void
    {
        header($header . self::HEADER_DECLARATOR . $value, $replace);
    }

    /**
     * Helper function to set the content header
     *
     * @param string $contentType
     * @param bool $replace
     * @return void
     */
    public static function setContentHeader(string $contentType, bool $replace = true): void
    {
        self::setCustomHeader(self::HEADER_KEY_CONTENT_TYPE, $contentType, $replace);
    }

    /**
     * Simply set the header content type to HTML
     * @return void
     */
    public static function setHTMLHeader(): void
    {
        self::setContentHeader(self::HTML);
    }

    /**
     * Simply set the header content type to JSON
     * @return void
     */
    public static function setJSONHeader(): void
    {
        self::setContentHeader(self::JSON);
    }

    /**
     * Simply set the header content type to plain TEXT
     * @return void
     */
    public static function setPlainTextHeader(): void
    {
        self::setContentHeader(self::TEXT_PLAIN);
    }

    /**
     * Simply set the header content type to XML
     * @return void
     */
    public static function setXMLHeader(): void
    {
        self::setContentHeader(self::XML);
    }

    /**
     * Simply set the header content type to plain TEXT and
     * additionaly set the content transfer to base64
     * @return void
     */
    public static function setJWTPlainHeader(): void
    {
        self::setPlainTextHeader();
        self::setCustomHeader(self::HEADER_KEY_CONTENT_TRANSFER, self::TRANSFER_BASE64, true);
    }
}
