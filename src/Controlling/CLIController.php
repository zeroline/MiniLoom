<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Controlling
 *
 */

namespace zeroline\MiniLoom\Controlling;

use RuntimeException;
use zeroline\MiniLoom\Controlling\Controller as Controller;

class CLIController extends Controller
{
    protected const FORMAT_DATE_LONG = 'Y-m-d H:i:s';

    protected const LOG_LEVEL_INFO = 'INFO';
    protected const LOG_LEVEL_WARNING = 'WARNING';
    protected const LOG_LEVEL_ERROR = 'ERROR';
    protected const LOG_LEVEL_DEBUG = 'DEBUG';

    /**
     *
     * @return string
     */
    protected function getDateForOutput(): string
    {
        return '[' . date(self::FORMAT_DATE_LONG) . ']';
    }

    /**
     *
     * @param mixed $data
     */
    protected function out(mixed $data) : void
    {
        if (!is_string($data)) {
            $data = print_r($data, true);
        }
        echo $data;
        flush();
    }

    /**
     *
     * @param mixed $data
     * @return void
     */
    protected function outLine(mixed $data) : void
    {
        if (!is_string($data)) {
            $data = print_r($data, true);
        }
        echo $data . PHP_EOL;
        flush();
    }

    /**
     *
     * @param string $question
     * @param string $positive
     * @param string $negative
     * @return bool
     */
    protected function confirm(string $question, string $positive = 'y', string $negative = 'n'): bool
    {
        $this->out($question . ' (' . $positive . '/' . $negative . ') ');
        try {
            $handle = fopen("php://stdin", "r");
            if (!$handle) {
                throw new RuntimeException("Can't open STDIN");
            }
            $line = fgets($handle);
            if (!$line) {
                throw new RuntimeException("Can't read STDIN");
            }
            fclose($handle);
            if (strtolower(trim($line)) == $positive) {
                return true;
            }
        } catch (RuntimeException $e) {
            $this->logError($e->getMessage());
            return false;
        } finally {
            if (isset($handle) && is_resource($handle)) {
                fclose($handle);
            }
        }

        return false;
    }

    /**
     * @param array<mixed> $dataToLog
     * @param string $type
     * @return void
     */
    private function log(array $dataToLog, string $type): void
    {
        $printData = array();
        foreach ($dataToLog as $data) {
            if (!is_string($data)) {
                $printData[] =  print_r($data, true);
            } else {
                $printData[] = $data;
            }
        }
        $printDataString = implode(' ', $printData);

        $typeString = '';
        switch ($type) {
            case self::LOG_LEVEL_WARNING:
                $typeString = "\033[33mWARNING\033[0m";
                break;
            case self::LOG_LEVEL_ERROR:
                $typeString = "\033[31mERROR\033[0m";
                break;
            case self::LOG_LEVEL_DEBUG:
                $typeString = 'DEBUG';
                break;
            case self::LOG_LEVEL_INFO:
            default:
                $typeString = "\033[34mINFO\033[0m";
                break;
        }
        $this->out($this->getDateForOutput() . ' [' . $typeString . '] ' . $printDataString . PHP_EOL);
    }

    /**
     * Log a debug message to the console
     *
     * @return void
     */
    protected function logDebug(): void
    {
        $this->log(func_get_args(), self::LOG_LEVEL_DEBUG);
    }

    /**
     * Log an info message to the console
     *
     * @return void
     */
    protected function logInfo(): void
    {
        $this->log(func_get_args(), self::LOG_LEVEL_INFO);
    }

    /**
     * Log a warning message to the console
     *
     * @return void
     */
    protected function logWarning(): void
    {
        $this->log(func_get_args(), self::LOG_LEVEL_WARNING);
    }

    /**
     * Log an error message to the console
     *
     * @return void
     */
    protected function logError(): void
    {
        $this->log(func_get_args(), self::LOG_LEVEL_ERROR);
    }
}
