<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 * Test case for DataContainer class
 */

namespace zeroline\MiniLoom\Tests\Data;

use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\Data\DataContainer as DataContainer;

class DataContainerTest extends TestCase
{
    public function testContainerConstructor()
    {
        $data = new DataContainer(['test' => 'test']);
        $this->assertEquals('test', $data->get('test'));
        $this->assertEquals('test', $data->test);
        $this->assertTrue(isset($data->test));

        $this->assertFalse(isset($data->test2));
    }

    public function testContainerDynamicData()
    {
        $data = new DataContainer();
        $data->test = 'test';
        $this->assertEquals('test', $data->get('test'));
        $this->assertEquals('test', $data->test);
        $this->assertTrue(isset($data->test));

        $this->assertFalse(isset($data->test2));
    }

    public function testReturnCompleteData()
    {
        $data = new DataContainer(['test' => 'test']);
        $this->assertEquals(['test' => 'test'], $data->getData());
    }

    public function testJsonSerializeImplementation()
    {
        $data = new DataContainer(['test' => 'test']);
        $this->assertEquals('{"test":"test"}', json_encode($data));
    }
}
