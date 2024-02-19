<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\DataIntegrity
 *
 */

namespace zeroline\MiniLoom\Modules\DataIntegrity;

use zeroline\MiniLoom\Moduling\IModule;

class Module implements IModule
{

    /**
     *
     * @return string
     */
    public function getModuleName(): string
    {
        return 'DataIntegrity';
    }

    /**
     *
     * @return \zeroline\MiniLoom\Routing\HTTP\RegisteredRoute[]
     */
    public function getRoutes(): array
    {
        return array();
    }

    /**
     *
     * @return \zeroline\MiniLoom\Routing\CLI\RegisteredCommand[]
     */
    public function getCommands(): array
    {
        return array();
    }

    /**
     *
     * @return string[]
     */
    public function getMigrations(): array
    {
        return array();
    }
}
