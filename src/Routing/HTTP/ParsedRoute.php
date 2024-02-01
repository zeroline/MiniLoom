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

class ParsedRoute
{
    public function __construct(public HTTPVerb $httpVerb, public string $route)
    {
    }
}
