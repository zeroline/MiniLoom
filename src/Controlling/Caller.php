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
use zeroline\MiniLoom\Controlling\BaseController as BaseController;
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
        // Check if class is BaseController
        if (is_subclass_of($fullClassName, BaseController::class)) {
            try {
                $instance = ObjectFactory::singleton($fullClassName);
                $callable = array($instance, $method);
                if (is_callable($callable)) {
                    return call_user_func_array($callable, $arguments);
                } else {
                    throw new RuntimeException("Method is not callable: " . $method . " in class: " . $fullClassName . " with arguments: " . json_encode($arguments));
                }
            } catch (Throwable $e) {
                throw new RuntimeException("Error while calling method: " . $method . " in class: " . $fullClassName . " with arguments: " . json_encode($arguments) . " Error: " . $e->getMessage());
            }
        } else {
            throw new RuntimeException("Class is not a BaseController: " . $fullClassName);
        }
    }
}
