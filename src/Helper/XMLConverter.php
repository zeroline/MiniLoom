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
use Exception;

final class XMLConverter
{
    /**
     *
     * @param array<string, mixed>|object $data
     * @param SimpleXMLElement $parent
     * @param string $rootElement
     * @return string
     *
     * @throws Exception
     */
    public static function toXML(array|object $data, ?SimpleXMLElement $parent = null, string $rootElement = '<root/>') : string
    {
        if (is_null($parent)) {
            $parent = new SimpleXMLElement($rootElement);
        }

        if (!is_array($data)) {
            $data = (array) $data;
        }

        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                static::toXML($value, $parent->addChild($key));
            } else {
                $parent->addChild($key, $value);
            }
        }

        $result = $parent->asXML();
        if ($result !== false) {
            return $result;
        } else {
            throw new Exception('Could not convert to XML');
        }
    }
}
