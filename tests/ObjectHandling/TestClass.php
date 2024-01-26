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

class TestClass
{
    public string $test;

    public function __construct(string $test = '')
    {
        $this->test = $test;
    }
}
