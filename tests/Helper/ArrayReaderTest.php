<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 * 
 * Test case for ArrayReader
 */

namespace zeroline\MiniLoom\Tests\Helper;

use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\Helper\ArrayReader;

final class ArrayReaderTest extends TestCase
{
    const LEVEL1_KEY = 'level1';
    const LEVEL1_VALUE_KEY = 'level1Value';
    const LEVEL1_VALUE = 'value1';
    const LEVEL2_KEY = 'level2';
    const LEVEL2_VALUE_KEY = 'level2Value';
    const LEVEL2_VALUE = 'value2';
    const LEVEL1_VALUE_PATH = self::LEVEL1_KEY . '.' . self::LEVEL1_VALUE_KEY;
    const INVALID_PATH = 'invalid.path';
    const GENERIC_FALLBACK = 'fallback';
    const ARRAY = [
        self::LEVEL1_KEY => [
            self::LEVEL2_KEY => [
                self::LEVEL2_VALUE_KEY => self::LEVEL2_VALUE
            ],
            self::LEVEL1_VALUE_KEY => self::LEVEL1_VALUE,
        ]
    ];

    public function testSimpleGetConfig(): void
    {
        $this->assertSame(
            self::LEVEL1_VALUE,
            ArrayReader::get(self::ARRAY, self::LEVEL1_VALUE_PATH)
        );
    }

    public function testSimpleGetConfigWithFallback(): void
    {
        $this->assertSame(
            self::GENERIC_FALLBACK,
            ArrayReader::get(self::ARRAY, self::INVALID_PATH, self::GENERIC_FALLBACK)
        );
    }

    public function testSimpleGetConfigWithFallbackToNull(): void
    {
        $this->assertSame(
            null,
            ArrayReader::get(self::ARRAY, self::INVALID_PATH)
        );
    }

    public function testSimpleGetConfigWithFallbackToCallable(): void
    {
        $this->assertSame(
            self::LEVEL2_VALUE,
            ArrayReader::get(self::ARRAY, self::INVALID_PATH, function() {
                return self::LEVEL2_VALUE;
            })
        );
    }

    public function testDeepGetConfig(): void
    {
        $this->assertSame(
            self::LEVEL2_VALUE,
            ArrayReader::get(self::ARRAY, self::LEVEL1_KEY . '.' . self::LEVEL2_KEY . '.' . self::LEVEL2_VALUE_KEY)
        );
    }
}