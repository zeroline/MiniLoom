<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 * Test case for TempFileHelper
 */

namespace zeroline\MiniLoom\Tests\Helper;

use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\Helper\TempFileHelper;

final class TempFileHelperTest extends TestCase
{
    const DATA = 'test data';
    const PREFIX = 'test';

    public function testPrefix(): void
    {
        $temp_file = TempFileHelper::write(self::DATA, self::PREFIX);
        $this->assertStringContainsString(
            self::PREFIX,
            $temp_file
        );
    }

    public function testWriteRead(): void
    {
        $temp_file = TempFileHelper::write(self::DATA, self::PREFIX);
        $this->assertStringContainsString(
            self::DATA,
            TempFileHelper::read($temp_file)
        );
    }

    public function testWithoutPrefix(): void
    {
        $temp_file = TempFileHelper::write(self::DATA);
        $this->assertStringNotContainsString(
            self::PREFIX,
            $temp_file
        );
    }
}
