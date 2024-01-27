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
    /**
     *
     * @var array<mixed>
     */
    private array $arguments = array();

    /**
     *
     * @param array<mixed> $arguments
     * @return void
     */
    public function __construct(array $arguments = array())
    {
        $this->arguments = $arguments;
    }

    /**
     *
     * @return array<mixed>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     *
     * @return EventArgs
     */
    public static function empty(): EventArgs
    {
        return new EventArgs();
    }
}
