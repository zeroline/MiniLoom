<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Moduling
 *
 */

namespace zeroline\MiniLoom\Moduling;

use RuntimeException;
use zeroline\MiniLoom\Moduling\IModule;
use zeroline\MiniLoom\Routing\HTTP\Router as HTTPRouter;
use zeroline\MiniLoom\Routing\CLI\Router as CLIRouter;

final class ModuleManager
{
    /**
     *
     * @var array<IModule>
     */
    private static array $modules = array();

    /**
     *
     * @param IModule $module
     * @return void
     */
    public static function registerModule(IModule $module) : void
    {
        self::$modules[$module->getModuleName()] = $module;
    }

    /**
     *
     * @param string $moduleName
     * @return IModule
     * @throws RuntimeException
     */
    public static function getModule(string $moduleName): IModule
    {
        if (array_key_exists($moduleName, self::$modules)) {
            return self::$modules[$moduleName];
        }
        throw new RuntimeException("Module not found: " . $moduleName);
    }

    /**
     *
     * @return array<IModule>
     */
    public static function getModules(): array
    {
        return self::$modules;
    }

    /**
     *
     * @param HTTPRouter $router
     * @return void
     */
    public static function registerRoutes(HTTPRouter $router) : void
    {
        foreach (self::$modules as $module) {
            $routes = $module->getRoutes();
            foreach ($routes as $route) {
                $router->on($route->httpVerb, $route->route, $route->controller, $route->method);
            }
        }
    }

    /**
     *
     * @param CLIRouter $router
     * @return void
     */
    public static function registerCommands(CLIRouter $router) : void
    {
        foreach (self::$modules as $module) {
            $commands = $module->getCommands();
            foreach ($commands as $command) {
                $router->on($command->command, $command->controller, $command->method);
            }
        }
    }
}
