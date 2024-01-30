<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Controlling
 *
 */

namespace zeroline\MiniLoom\Controlling;

use zeroline\MiniLoom\ObjectHandling\ObjectFactory as ObjectFactory;
use zeroline\MiniLoom\Controlling\Controller as Controller;
use RuntimeException;
use Throwable;

final class Caller
{
    /**
     * Calls a controller method with arguments.
     * @param string $fullClassName
     * @param string $method
     * @param array<mixed> $arguments
     * @return mixed
     */
    public static function call(string $fullClassName, string $method, array $arguments = array()) : mixed
    {
        // Check if class is Controller
        if (is_subclass_of($fullClassName, Controller::class)) {
            try {
                return ObjectFactory::singleton($fullClassName)->$method(...$arguments);
            } catch (Throwable $e) {
                throw new RuntimeException("Error while calling method: " . $method . " in class: " . $fullClassName . " with arguments: " . json_encode($arguments) . " Error: " . $e->getMessage());
            }
        } else {
            throw new RuntimeException("Class is not a Controller: " . $fullClassName);
        }
    }
}
