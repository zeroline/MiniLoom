<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Routing
 *
 */

namespace zeroline\MiniLoom\Routing\CLI;

class ParsedCommand
{
    /**
     *
     * @param string $command
     * @param array<mixed> $arguments
     * @return void
     */
    public function __construct(public string $command, public array $arguments)
    {
    }
}
