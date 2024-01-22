<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 * Test case for ArrayReader
 */

namespace zeroline\MiniLoom\Tests\ObjectHandling;

use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\ObjectHandling\ObjectFactory as ObjectFactory;
use zeroline\MiniLoom\Tests\ObjectHandling\TestClass as TestClass;
use zeroline\MiniLoom\Tests\ObjectHandling\TestClassWithSingletonTrait as TestClassWithSingletonTrait;
use zeroline\MiniLoom\Tests\ObjectHandling\TestClassWithSingletonTraitAndAnnotations as TestClassWithSingletonTraitAndAnnotations;

final class ObjectFactoryTest extends TestCase
{
    public function testCreate()
    {
        $object = ObjectFactory::create(TestClass::class);
        $this->assertInstanceOf(TestClass::class, $object);
    }

    public function testSingleton()
    {
        $object = ObjectFactory::singleton(TestClass::class);
        $this->assertInstanceOf(TestClass::class, $object);
    }

    public function testSingletonTraitWithArguments()
    {
        $object = TestClassWithSingletonTrait::getInstance('test');
        $this->assertInstanceOf(TestClassWithSingletonTrait::class, $object);
        $this->assertEquals('test', $object->test);
    }

    public function testSingletonTraitWithArgumentsAndAnnotations()
    {
        $object = TestClassWithSingletonTraitAndAnnotations::getInstance('test');
        $this->assertInstanceOf(TestClassWithSingletonTraitAndAnnotations::class, $object);
        $this->assertEquals('test', $object->test);
        $this->assertInstanceOf(TestClass::class, $object->testClass);
    }
}