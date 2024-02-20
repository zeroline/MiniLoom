<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\JobManagement
 *
 */

namespace zeroline\MiniLoom\Modules\JobManagement;

use zeroline\MiniLoom\Moduling\IModule;
use zeroline\MiniLoom\Routing\CLI\RegisteredCommand;

use zeroline\MiniLoom\Modules\JobManagement\Commands\ManagerCommandController;
use zeroline\MiniLoom\Modules\JobManagement\Commands\WorkerCommandController;

class Module implements IModule
{

    /**
     *
     * @return string
     */
    public function getModuleName(): string
    {
        return 'JobManagement';
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
            new RegisteredCommand('jm.list', ManagerCommandController::class, 'list'),
            new RegisteredCommand('jw.run', WorkerCommandController::class, 'run'),
        );
    }

    /**
     *
     * @return string[]
     */
    public function getMigrations(): array
    {
        $baseDir = __DIR__.DIRECTORY_SEPARATOR.'Migrations'.DIRECTORY_SEPARATOR;
        return array(
            $baseDir.'202001281220.sql'
        );
    }
}
