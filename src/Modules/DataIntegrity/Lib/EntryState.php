<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\DataIntegrity
 *
 */

namespace zeroline\MiniLoom\Modules\DataIntegrity\Lib;

final class EntryState
{
    /**
     * Standard state for every entry. Indicates that the entry is in the default state, neither deleted or
     * inactive.
     *
     * @var int
     */
    public const ACTIVE = 100;

    /**
     * A special state that the entry has been kind of suspended but not deleted
     * @var int
     */
    public const INACITVE = 200;

    /**
     * The entry has been deleted
     * @var int
     */
    public const DELETED = 300;

    /**
     * The entry should be deleted permanently. for example to be GDPR conform
     * @var int
     */
    public const REQUEST_FOR_PERM_DELETION = 666;
}
