<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Routing
 *
 */

namespace zeroline\MiniLoom\Routing;

use zeroline\MiniLoom\Controlling\Caller as Caller;
use Exception;

class CLIRouter
{
    public function on(string $command, string $controller, string $method) : void
    {
        $argv = isset($GLOBALS['argv']) ? $GLOBALS['argv'] : array();
        if (isset($argv[1]) && $argv[1] === $command) {
            array_shift($argv);
            array_shift($argv);
            $arguments = array();
            for ($i = 0, $j = count($argv); $i < $j; $i++) {
                $arg = $argv[$i];
                $key = $value = null;
                // --arg --arg=baz
                if (substr($arg, 0, 2) === '--') {
                    $eqPos  = strpos($arg, '=');
                    // --arg
                    if ($eqPos === false) {
                        $key = substr($arg, 2);
                         // --arg value
                        if ($i + 1 < $j && $argv[$i + 1][0] !== '-') {
                            $value  = $argv[$i + 1];
                            $i++;
                        } else {
                            $value = isset($arguments[$key]) ? $arguments[$key] : true;
                        }
                        $arguments[$key]  = $value;
                    } else {
                        $key = substr($arg, 2, $eqPos - 2);
                        $value  = substr($arg, $eqPos + 1);
                        $arguments[$key]  = $value;
                    }
                } elseif (substr($arg, 0, 1) === '-') {
                    // -arg=value
                    if (substr($arg, 2, 1) === '=') {
                        $key = substr($arg, 1, 1);
                        $value  = substr($arg, 3);
                        $arguments[$key]  = $value;
                    } else {
                        $chars  = str_split(substr($arg, 1));
                        foreach ($chars as $char) {
                                $key = $char;
                                $value  = isset($arguments[$key]) ? $arguments[$key] : true;
                                $arguments[$key]  = $value;
                        }
                        // -arg value1 -abc value2
                        if ($i + 1 < $j && $argv[$i + 1][0] !== '-') {
                            $arguments[$key]  = $argv[$i + 1];
                            $i++;
                        }
                    }
                } else {
                    $value  = $arg;
                    $arguments[]  = $value;
                }
            }
            Caller::call($controller, $method, $arguments);
        }
    }

    public function onNotFound(): void
    {
        throw new Exception('Command not found');
    }
}
