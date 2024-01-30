<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Controlling
 *
 */

namespace zeroline\MiniLoom\Controlling;

use zeroline\MiniLoom\ObjectHandling\SingletonTrait;
use zeroline\MiniLoom\Controlling\Middleware as Middleware;
use zeroline\MiniLoom\Event\Mediator as Mediator;
use Exception;
use ReflectionException;

class Controller
{
    use SingletonTrait;

    /**
     *
     * @var Mediator
     */
    protected Mediator $mediator;

    /**
     *
     * @var array<string, mixed>
     */
    protected array $middleware = array();

    /**
     *
     * @var array<mixed>
     */
    protected array $middlewareData = array();

    /**
     * @var string
     */
    protected string $methodToCall;

    /**
     *
     * @return void
     * @throws ReflectionException
     */
    public function __construct()
    {
        $this->mediator = Mediator::getInstance();
    }

    /**
     *
     * @param string $fullClassName
     * @param array<mixed> $config
     */
    protected function addMiddleware(string $fullClassName, array $config = array()) : void
    {
        $this->middleware[$fullClassName] = $config;
    }

    /**
     *
     * @return array<string, mixed>
     */
    private function getMiddlewares(): array
    {
        return $this->middleware;
    }

    /**
     *
     * @param string $middlewareClass
     * @param string $fieldName
     * @param mixed $value
     */
    public function injectMiddlewareField(string $middlewareClass, string $fieldName, $value) : void
    {
        if (!array_key_exists($middlewareClass, $this->middlewareData)) {
            $this->middlewareData[$middlewareClass] = array();
        }
        $this->middlewareData[$middlewareClass][$fieldName] = $value;
    }

    /**
     *
     * @param string $middlewareClass
     * @param string $fieldName
     * @param mixed $fallback
     * @return mixed
     */
    public function getInjectedMiddlewareField(string $middlewareClass, string $fieldName, mixed $fallback = null) : mixed
    {
        if (array_key_exists($middlewareClass, $this->middlewareData)) {
            if (array_key_exists($fieldName, $this->middlewareData[$middlewareClass])) {
                return $this->middlewareData[$middlewareClass][$fieldName];
            }
        }
        return $fallback;
    }

    /**
     *
     * @param string $name
     * @param array<mixed> $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call(string $name, array $arguments) : mixed
    {
        $callable = array($this, $name);
        if (!method_exists($this, $name)) {
            throw new Exception("Method not found: " . $name, 500);
        }
        if (is_callable($callable)) {
            $this->methodToCall = $name;
            foreach ($this->getMiddlewares() as $middlewareClass => $config) {
                $middleware = new $middlewareClass();
                if ($middleware instanceof Middleware) {
                    $middleware->setConfig($config);
                    $middleware->setController($this);
                    $middleware->setCalledMethod($name, $arguments);
                    $result = $middleware->process();
                    if ($result !== true) {
                        return $result;
                    }
                } else {
                    throw new Exception("Invalid middleware class type.", 500);
                }
            }
            return call_user_func_array($callable, $arguments);
        }
        return null;
    }
}
