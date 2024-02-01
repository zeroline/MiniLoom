<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Controlling
 *
 */

namespace zeroline\MiniLoom\Controlling;

use zeroline\MiniLoom\Controlling\BaseController as BaseController;

abstract class Middleware
{
    /**
     *
     * @var string
     */
    private string $calledMethod;

    /**
     *
     * @var array<mixed>
     */
    private array $calledMethodArguments;

    /**
     *
     * @var BaseController
     */
    private BaseController $controller;

    /**
     *
     * @var array<mixed>
     */
    private array $config = array();

    /**
     *
     * @param string $method
     * @param array<mixed> $arguments
     */
    public function setCalledMethod(string $method, array $arguments = array()) : void
    {
        $this->calledMethod = $method;
        $this->calledMethodArguments = $arguments;
    }

    /**
     *
     * @param BaseController $controller
     */
    public function setController(BaseController $controller) : void
    {
        $this->controller = $controller;
    }

    /**
     *
     * @param array<mixed> $config
     */
    public function setConfig(array $config = array()) : void
    {
        $this->config = $config;
    }

    /**
     *
     * @param string $key
     * @param mixed $fallback
     * @return mixed
     */
    protected function getConfig(string $key, mixed $fallback = null) : mixed
    {
        $value = (array_key_exists($key, $this->config) ? $this->config[$key] : $fallback);
        return $value;
    }

    /**
     *
     * @return string
     */
    protected function getCalledMethod() : string
    {
        return $this->calledMethod;
    }

    /**
     *
     * @return array<mixed>
     */
    protected function getCalledMethodArguments() : array
    {
        return $this->calledMethodArguments;
    }

    /**
     *
     * @return BaseController
     */
    protected function getController() : BaseController
    {
        return $this->controller;
    }

    /**
     *
     */
    abstract public function process() : bool;
}
