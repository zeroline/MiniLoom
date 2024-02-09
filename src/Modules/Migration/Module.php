<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\Migration
 *
 */

namespace zeroline\MiniLoom\Modules\Migration;

use zeroline\MiniLoom\Moduling\IModule;
use zeroline\MiniLoom\Routing\HTTP\RegisteredRoute;
use zeroline\MiniLoom\Routing\CLI\RegisteredCommand;
use zeroline\MiniLoom\Modules\Migration\Commands\MigrationCommandController;

class Module implements IModule
{

    /**
     *
     * @return string
     */
    public function getModuleName(): string
    {
        return 'Migration';
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
        return array(
            new RegisteredCommand('migration.status', MigrationCommandController::class, 'status'),
            new RegisteredCommand('migration.init', MigrationCommandController::class, 'init'),
        );
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
