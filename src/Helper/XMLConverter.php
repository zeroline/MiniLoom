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

final class XMLConverter
{
    /**
     *
     * @param array|object $data
     * @param \SimpleXMLElement $parent
     * @return string
     */
    public static function toXML($data, $parent = null, $rootElement = '<root/>')
    {
        if (is_null($parent)) {
            $parent = new \SimpleXMLElement($rootElement);
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
