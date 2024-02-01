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
use zeroline\MiniLoom\Tests\Data\Filter\FilterModel as FilterModel;

class FilterModelTest extends TestCase
{
    public function testFilterModel() : void
    {
        $model = new FilterModel(array('test_strip' => '<p>Test</p>', 'test_encode' => '<p>Test</p>'));
        $model->filter();
        $this->assertEquals($model->test_strip, 'Test');
        $this->assertEquals($model->test_encode, '&lt;p&gt;Test&lt;/p&gt;');
        $this->assertInstanceOf(FilterModel::class, $model);
    }
}
