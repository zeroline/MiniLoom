<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Event
 *
 */

namespace zeroline\MiniLoom\Event;

use zeroline\MiniLoom\ObjectHandling\SingletonTrait as SingletonTrait;

class Mediator
{
    use SingletonTrait;

    /**
     * Store all events with handlers
     *
     * @var array<string, array<callable>>
     */
    private array $events = array();

    /**
     * Trigger one specific event
     *
     * @param string $eventName
     * @param EventArgs $args
     * @return void
     */
    public function trigger(string $eventName, EventArgs $args = null): void
    {
        if (is_null($args)) {
            $args = EventArgs::empty();
        }

        if (array_key_exists($eventName, $this->events)) {
            foreach ($this->events[$eventName] as $eventHandler) {
            //$eventHandler($args);
                call_user_func_array($eventHandler, array($args));
            }
        } else {
            // Do not throw an exception or else.
            // There could be no handled events at some point.
        }
    }

    /**
     * Handle one specific event
     *
     * @param string $eventName
     * @param callable $eventHandler
     * @return void
     */
    public function on(string $eventName, callable $eventHandler): void
    {
        if (!array_key_exists($eventName, $this->events)) {
            $this->events[$eventName] = array();
        }
        $this->events[$eventName][] = $eventHandler;
    }
}
