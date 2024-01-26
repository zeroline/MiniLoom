<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage ObjectHandling
 *
 * The AnnotationParser provides a parser for annotations.
 * It is possible to parse annotations from a doc comment.
 * The parser can be used to inject classes and components into an object.
 */

namespace zeroline\MiniLoom\ObjectHandling;

use ReflectionException;
use ReflectionObject;
use ReflectionClass;
use zeroline\MiniLoom\ObjectHandling\ObjectFactory as ObjectFactory;

final class AnnotationParser
{
    const INJECTION_PARAMETER = 'inject';
    const INJECTION_PARAMETER_SINGLETON = 'singleton';
    const INJECTION_PARAMETER_INSTANCE = 'instance';
    const INJECTION_PARAMETER_OPTIONS = 'options';

    /**
     * Parses the doc comments of a class or method.
     * 
     * @param string $comment 
     * @return array 
     */
    private static function resolveParamterFromDocComment(string $comment) : array
    {
        $keyPattern = "[A-z0-9\_\-]+";
        $endPattern = "[ ]*(?:@|\r\n|\n)";
        $pattern = "/@(?=(.*)" . $endPattern . ")/U";
        $foundParameter = array();
        preg_match_all($pattern, $comment, $matches);
        foreach ($matches[1] as $rowMatch) {
            if (preg_match("/^(" . $keyPattern . ") (.*)$/", $rowMatch, $match)) {
                $parsedValue = isset($match[2]) ? $match[2] : null;
                if (isset($foundParameter[$match[1]])) {
                    $foundParameter[$match[1]] = array_merge((array)$foundParameter[$match[1]], (array)$parsedValue);
                } else {
                    $foundParameter[$match[1]] = $parsedValue;
                }
            } elseif (preg_match("/^" . $keyPattern . "$/", $rowMatch, $match)) {
                $foundParameter[$rowMatch] = true;
            } else {
                $foundParameter[$rowMatch] = null;
            }
        }
        return $foundParameter;
    }

    /**
     * Checks if a parameter exists in a doc comment.
     * @param string $name 
     * @param string $comment 
     * @return bool 
     */
    private static function hasParameter(string $name, string $comment) : bool
    {
        $parameter = static::resolveParamterFromDocComment($comment);
        return array_key_exists($name, $parameter);
    }

    /**
     * Resolves a parameter value from a doc comment.
     * @param string $parameter 
     * @param string $comment 
     * @return null|string 
     */
    private static function resolveParamterValueFromDocComment(string $parameter, string $comment) : ?string
    {
        if (static::hasParameter($parameter, $comment)) {
            return static::resolveParamterFromDocComment($comment)[$parameter];
        }
        return null;
    }

    /**
     * Returns all properties of a class.
     * @param ReflectionObject $ref 
     * @return array 
     */
    private static function getClassProperties(ReflectionObject $ref) : array
    {
        $props = $ref->getProperties();
        $props_arr = array();
        foreach ($props as $prop) {
            $f = $prop->getName();
            $props_arr[] = $prop;
        }
        // TODO: check if parent class has properties and merge them in this object
        /*
        if (($parentClass = $ref->getParentClass())) {
            $parent_props_arr = static::getClassProperties(new ReflectionClass($parentClass->getName()));
            // recursive call
            if (count($parent_props_arr) > 0) {
                $props_arr = array_merge($parent_props_arr, $props_arr);
            }
        }
        */
        return $props_arr;
    }

    /**
     * Injects classes and components into an object.
     * @param mixed $object 
     * @return void 
     * @throws ReflectionException 
     */
    public static function injectClassesAndComponentsIntoObject(mixed $object) : void
    {
        $reflection = new ReflectionObject($object);
        $properties = static::getClassProperties($reflection);
        foreach ($properties as $property) {
            $property->setAccessible(true);
            
            if ($property->isInitialized($object) && !is_null($property->getValue($object))) {
                continue;
            }

            $comment = $property->getDocComment();
            $options = array();
            if (static::hasParameter(self::INJECTION_PARAMETER_OPTIONS, $comment)) {
                $optionString = static::resolveParamterValueFromDocComment(self::INJECTION_PARAMETER_OPTIONS, $comment);
                $options = explode(',', trim(str_replace(array('(',')'), array('',''), $optionString)));
            }

            if (static::hasParameter(self::INJECTION_PARAMETER, $comment)) {
                $value = static::resolveParamterValueFromDocComment(self::INJECTION_PARAMETER, $comment);
                if (strpos($value, '\\') !== false) {
                    $property->setValue($object, ObjectFactory::singleton($value, $options));
                }
            } elseif (static::hasParameter(self::INJECTION_PARAMETER_INSTANCE, $comment)) {
                $value = static::resolveParamterValueFromDocComment(self::INJECTION_PARAMETER_INSTANCE, $comment);
                if (strpos($value, '\\') !== false) {
                    $property->setValue($object, ObjectFactory::create($value, $options));
                }
            } elseif (static::hasParameter(self::INJECTION_PARAMETER_SINGLETON, $comment)) {
                $value = static::resolveParamterValueFromDocComment(self::INJECTION_PARAMETER_SINGLETON, $comment);
                if (strpos($value, '\\') !== false) {
                    $property->setValue($object, ObjectFactory::singleton($value, $options));
                }
            }
        }
    }
}
