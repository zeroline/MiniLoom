<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Moduling
 *
 */

namespace zeroline\MiniLoom\Moduling;

use zeroline\MiniLoom\Routing\HTTP\RegisteredRoute as RegisteredRoute;
use zeroline\MiniLoom\Routing\CLI\RegisteredCommand as RegisteredCommand;

interface IModule
{
    /**
     *
     * @return string
     */
    public function getModuleName(): string;

    /**
     *
     * @return array<RegisteredRoute>
     */
    public function getRoutes(): array;

    /**
     *
     * @return array<RegisteredCommand>
     */
    public function getCommands(): array;

    /**
     *
     * @return array<string>
     */
    public function getMigrations(): array;
}
