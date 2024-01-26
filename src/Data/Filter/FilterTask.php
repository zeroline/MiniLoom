<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 */

namespace zeroline\MiniLoom\Data\Filter;

enum FilterTask : string
{
    case FILTER_ENCODE_HTML = "filterEncodeHtml";
    case FILTER_STRIP_HTML = "filterStripHtml";

    case FILTER_CUSTOM = "filterCustom";
}
