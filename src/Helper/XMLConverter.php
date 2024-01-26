<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Helper
 *
 * The XMLConverter helps to convert an array or object to a XML string.
 */

namespace zeroline\MiniLoom\Helper;

use SimpleXMLElement;

final class XMLConverter
{
    /**
     *
     * @param array<string, mixed>|object $data
     * @param SimpleXMLElement $parent
     * @param string $rootElement
     * @return string
     */
    public static function toXML(array|object $data, ?SimpleXMLElement $parent = null, string $rootElement = '<root/>')
    {
        if (is_null($parent)) {
            $parent = new SimpleXMLElement($rootElement);
        }

        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                static::toXML($value, $parent->addChild($key));
            } else {
                $parent->addChild($key, $value);
            }
        }

        return $parent->asXML();
    }
}
