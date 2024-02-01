<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 * Test case for the Filter class
 */

namespace zeroline\MiniLoom\Tests\Data\Filter;

use zeroline\MiniLoom\Data\Database\SQL\ValidationModel as ValidationModel;
use zeroline\MiniLoom\Data\Filter\FilterTask as FilterTask;

class FilterModel extends ValidationModel
{
    protected array $fieldsForFiltering = array(
        'test_strip' => array(FilterTask::FILTER_STRIP_HTML => array()),
        'test_encode' => array(FilterTask::FILTER_ENCODE_HTML => array()),
    );
}
