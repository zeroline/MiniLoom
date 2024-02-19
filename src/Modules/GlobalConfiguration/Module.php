<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\GlobalConfiguration
 *
 */

namespace zeroline\MiniLoom\Modules\GlobalConfiguration;

use zeroline\MiniLoom\Moduling\IModule;
use zeroline\MiniLoom\Routing\CLI\RegisteredCommand;
use zeroline\MiniLoom\Modules\GlobalConfiguration\Commands\ManagerCommandController;

class Module implements IModule
{

    /**
     *
     * @return string
     */
    public function getModuleName(): string
    {
        return 'GlobalConfiguration';
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
            new RegisteredCommand('gconfig.overview', ManagerCommandController::class, 'overview'),
            new RegisteredCommand('gconfig.update', ManagerCommandController::class, 'update'),
            new RegisteredCommand('gconfig.deleteSector', ManagerCommandController::class, 'deleteSector'),
            new RegisteredCommand('gconfig.deleteSection', ManagerCommandController::class, 'deleteSection'),
            new RegisteredCommand('gconfig.deleteField', ManagerCommandController::class, 'deleteField'),
        );
    }

    /**
     *
     * @return string[]
     */
    public function getMigrations(): array
    {
        return array(
            __DIR__.DIRECTORY_SEPARATOR.'Migrations'.DIRECTORY_SEPARATOR.'20180613152200.sql'
        );
    }
}
