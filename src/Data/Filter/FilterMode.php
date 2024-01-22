<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 */

namespace zeroline\MiniLoom\Data\Filter;

enum FilterMode : string
{
    case FILTER_MODE_BEFORE_SAVE = 'beforeSave';
    case FILTER_MODE_AFTER_AFTER_FETCH = 'afterFetch';
    case FILTER_MODE_BOTH = 'both';
}
