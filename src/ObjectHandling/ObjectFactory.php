<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage ObjectHandling
 *
 * The ObjectFactory provides a factory pattern for classes.
 * It is possible to create a new instance or a singleton instance.
 * The singleton instance is stored in the static $instances array if the class does not use the SingletonTrait.
 * If the class uses the SingletonTrait the instance is stored in the static $instances array of the class provided by the trait.
 */

namespace zeroline\MiniLoom\ObjectHandling;

use zeroline\MiniLoom\ObjectHandling\AnnotationParser as AnnotationParser;
use zeroline\MiniLoom\ObjectHandling\SingletonTrait as SingletonTrait;

class ObjectFactory
{
    /**
     * Store singleton instances
     *
     * @var array
     */
    private static $instances = array();

    /**
     * Creates a new instance of a class.
     * @param string $class
     * @param boolean $enableAnnotations
     * @return $class
     */
    public static function create($class, array $args = array(), $enableAnnotations = true) : mixed
    {
        $reflector = new \ReflectionClass($class);
        $instance = $reflector->newInstanceArgs($args);
        if ($enableAnnotations) {
            AnnotationParser::injectClassesAndComponentsIntoObject($instance);
        }
        return $instance;
    }

    /**
     * Creates a singleton instance of a class.
     * If the class does not use the SingletonTrait the instance is stored in the static $instances array.
     * @param string $class
     * @param boolean $enableAnnotations
     * @return $class
     */
    public static function singleton($class, array $args = array(), $enableAnnotations = true) : mixed
    {
        if (in_array(SingletonTrait::class, class_uses($class))) {
            return $class::getInstance(...$args);
        } elseif (!array_key_exists($class, static::$instances)) {
            static::$instances[$class] = static::create($class, $args, $enableAnnotations);
        }

        return static::$instances[$class];
    }
}
