<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Routing
 *
 */

namespace zeroline\MiniLoom\Routing\CLI;

class RegisteredCommand
{
    public function __construct(public string $command, public string $controller, public string $method)
    {
    }
}
