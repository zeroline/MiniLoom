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
use ReflectionClass;
use Exception;

class ObjectFactory
{
    /**
     * Store singleton instances
     *
     * @var array<string, mixed>
     */
    protected static $instances = array();

    /**
     * Creates a new instance of a class.
     * @param string $class
     * @param array<mixed> $args
     * @param boolean $enableAnnotations
     * @return mixed
     */
    public static function create(string $class, array $args = array(), $enableAnnotations = true) : mixed
    {
        if (!class_exists($class)) {
            throw new Exception("Class does not exist: " . $class);
        }
        $reflector = new ReflectionClass($class);
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
     * @param array<mixed> $args
     * @param boolean $enableAnnotations
     * @return mixed
     */
    public static function singleton(string $class, array $args = array(), $enableAnnotations = true) : mixed
    {
        if (!class_exists($class)) {
            throw new Exception("Class does not exist: " . $class);
        }
        if (in_array(SingletonTrait::class, class_uses($class))) {
            return $class::getInstance(...$args);
        } elseif (!array_key_exists($class, static::$instances)) {
            static::$instances[$class] = static::create($class, $args, $enableAnnotations);
        }

        return static::$instances[$class];
    }
}
