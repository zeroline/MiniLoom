<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\JobManagement
 *
 */

namespace zeroline\MiniLoom\Modules\JobManagement\Lib;

final class JobHandlingResultStatus
{
    /**
     * Indicates that everything is fine
     */
    public const SUCCESS = 1;

    /**
     * Something went wrong but may work next time
     */
    public const FAILED = 10;

    /**
     * Something did terribly go wrong, don't try again
     */
    public const ERROR = 100;
}
