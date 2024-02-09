<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Routing
 *
 */

namespace zeroline\MiniLoom\Routing\CLI;

use zeroline\MiniLoom\Controlling\Caller as Caller;
use zeroline\MiniLoom\Routing\CLI\ParsedCommand as ParsedCommand;
use zeroline\MiniLoom\Routing\CLI\RegisteredCommand as RegisteredCommand;

use Exception;
use RuntimeException;
use Throwable;

class Router
{
    public const ERROR_OUTPUT_SUPRESS = 0;
    public const ERROR_OUTPUT_SIMPLE = 1;
    public const ERROR_OUTPUT_VERBOSE = 2;

    private function drawLine() : string
    {
        return '----------------------------------------' . PHP_EOL;
    }

    /**
     *
     * @var array<string, RegisteredCommand>
     */
    protected array $registeredCommands = array();

    /**
     *
     * @param string $command
     * @return null|RegisteredCommand
     */
    protected function getRegisteredCommand(string $command) : ?RegisteredCommand
    {
        if (isset($this->registeredCommands[$command])) {
            return $this->registeredCommands[$command];
        }
        return null;
    }

    /**
     *
     * @return ParsedCommand
     */
    protected function parseInput() : ParsedCommand
    {
        $command = '';
        $arguments = array();

        $argv = isset($GLOBALS['argv']) ? $GLOBALS['argv'] : array();
        if (isset($argv[1])) {
            $command = $argv[1];
            array_shift($argv);
            array_shift($argv);
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
        }

        return new ParsedCommand($command, $arguments);
    }

    /**
     *
     * @return void
     * @throws Exception
     * @throws RuntimeException
     */
    public function processInput(int $errorOutputLevel = self::ERROR_OUTPUT_VERBOSE) : void
    {
        try {
            $inputCommand = $this->parseInput();
            if ($inputCommand->command == null) {
                throw new Exception('No command given');
            }
            $registeredCommand = $this->getRegisteredCommand($inputCommand->command);
            if ($registeredCommand == null) {
                throw new Exception('Command not registered');
            }

            $result = Caller::call($registeredCommand->controller, $registeredCommand->method, $inputCommand->arguments);
        } catch (Throwable $t) {
            $outputData = '';

            switch ($errorOutputLevel) {
                case self::ERROR_OUTPUT_SUPRESS:
                    break;
                case self::ERROR_OUTPUT_SIMPLE:
                    $outputData =
                        "\033[31mAn error occured:\033[0m".PHP_EOL.
                        $this->drawLine().
                        $t->getMessage().PHP_EOL.
                        $this->drawLine().
                        "";
                    break;
                case self::ERROR_OUTPUT_VERBOSE:
                    $outputData =
                        "\033[31mAn error occured:\033[0m".PHP_EOL.
                        $this->drawLine().
                        $t->getMessage().PHP_EOL.
                        $this->drawLine().
                        "";
                    $outputData .=
                        $t->getTraceAsString().PHP_EOL.
                        $this->drawLine();
                    break;
            }

            if (!empty($outputData)) {
                echo $outputData . PHP_EOL;
                flush();
            }
        }
    }

    /**
     *
     * @param string $command
     * @param string $controller
     * @param string $method
     * @return void
     */
    public function on(string $command, string $controller, string $method) : void
    {
        $this->registeredCommands[$command] = new RegisteredCommand($command, $controller, $method);
    }
}
