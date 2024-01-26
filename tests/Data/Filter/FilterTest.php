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

use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\Data\Filter\Filter as Filter;

class FilterTest extends TestCase
{
    public function testFilterEncodeHtml()
    {
        $this->assertEquals('&lt;test&gt;', Filter::filterEncodeHtml('<test>'));
    }

    public function testFilterStripHtml()
    {
        $this->assertEquals('Hello World', Filter::filterStripHtml('<p>Hello World</p'));
    }

    public function testFilterCustom()
    {
        $this->assertEquals('test filtered', Filter::filterCustom('test', function ($value) {
            return $value.' filtered';
        }));
    }
}
