<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 * Test case for StringHelper
 */

namespace zeroline\MiniLoom\Tests\Helper;

use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\Helper\StringHelper;

final class StringHelperTest extends TestCase
{
    public function testStartsWith(): void
    {
        $this->assertTrue(
            StringHelper::startsWith('foobar', 'foo')
        );
    }

    public function testEndsWith(): void
    {
        $this->assertTrue(
            StringHelper::endsWith('foobar', 'bar')
        );
    }

    public function testContains(): void
    {
        $this->assertTrue(
            StringHelper::contains('foobar', 'oob')
        );
    }
}
