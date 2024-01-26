<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 * Test class for ObjectFactoryTest
 */

namespace zeroline\MiniLoom\Tests\ObjectHandling;

use zeroline\MiniLoom\ObjectHandling\SingletonTrait;

class TestClassWithSingletonTraitAndAnnotations
{
    use SingletonTrait;

    /**
     * @inject zeroline\MiniLoom\Tests\ObjectHandling\TestClass
     * @var TestClass
     */
    public TestClass $testClass;

    public string $test;

    public function __construct(string $test = '')
    {
        $this->test = $test;
    }
}
