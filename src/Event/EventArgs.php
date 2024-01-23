<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Event
 *
 */

namespace zeroline\MiniLoom\Event;

class EventArgs
{
    private array $arguments = array();
    public function __construct(array $arguments = array())
    {
        $this->arguments = $arguments;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public static function empty(): EventArgs
    {
        return new EventArgs();
    }
}
