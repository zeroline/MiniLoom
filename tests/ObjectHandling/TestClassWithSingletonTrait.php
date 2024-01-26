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

class TestClassWithSingletonTrait
{
    use SingletonTrait;

    public string $test;

    public function __construct(string $args)
    {
        $this->test = $args;
    }
}
