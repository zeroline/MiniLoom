<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Routing
 *
 */

namespace zeroline\MiniLoom\Routing\HTTP;

use zeroline\MiniLoom\Routing\HTTP\HTTPVerb as HTTPVerb;

class RegisteredRoute
{
    /**
     *
     * @param HTTPVerb $httpVerb
     * @param string $route
     * @param string $controller
     * @param string $method
     * @return void
     */
    public function __construct(public HTTPVerb $httpVerb, public string $route, public string $controller, public string $method)
    {
    }
}
