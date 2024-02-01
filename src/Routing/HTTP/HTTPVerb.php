<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Routing
 *
 */

namespace zeroline\MiniLoom\Routing\HTTP;

use ValueError;

enum HTTPVerb
{
    case GET;
    case POST;
    case PUT;
    case DELETE;
    case HEAD;
    case OPTIONS;
    case TRACE;
    case CONNECT ;
    case PATCH;

    public static function fromName(string $name): HTTPVerb
    {
        foreach (self::cases() as $status) {
            if ( $name === $status->name ) {
                return $status;
            }
        }
        throw new ValueError("$name is not a valid backing value for enum " . self::class);
    }
}
