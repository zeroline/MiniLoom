<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 * Test case for Model class
 */

namespace zeroline\MiniLoom\Tests\Data;

use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\Data\Model as Model;

class ModelTest extends TestCase 
{
    public function testModelConstructor()
    {
        $model = new Model(['test' => 'test']);
        $this->assertEquals('test', $model->test);
        $this->assertTrue(isset($model->test));

        $this->assertFalse(isset($model->test2));
    }

    public function testModelDynamicData()
    {
        $model = new Model();
        $model->test = 'test';
        $this->assertEquals('test', $model->test);
        $this->assertTrue(isset($model->test));

        $this->assertFalse(isset($model->test2));
    }

    public function testJsonSerializeImplementation() {
        $model = new Model(['test' => 'test']);
        $this->assertEquals('{"test":"test"}', json_encode($model));
    }

    public function testDirtyFields() {
        $model = new Model(['test' => 'test']);
        $this->assertEquals([], $model->getDirtyFields());

        $model->test = 'test2';
        $this->assertEquals(['test' => 'test2'], $model->getDirtyFields());

        $model->test2 = 'test2';
        $this->assertEquals(['test' => 'test2', 'test2' => 'test2'], $model->getDirtyFields());

        $this->assertEquals(['test', 'test2'], $model->getDirtyFieldNames());
    }
}