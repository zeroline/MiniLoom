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
use zeroline\MiniLoom\Helper\CSVHelper;

class CSVHelperTest extends TestCase
{
    public function testEmptyBuild(): void
    {
        $this->assertSame(
            chr(255) . chr(254) . mb_convert_encoding("", "UCS-2LE", "auto"),
            CSVHelper::build([], [])
        );
    }

    public function testBuildWithData(): void
    {
        $this->assertSame(
            chr(255) . chr(254) . mb_convert_encoding('"0";"1";"2"'. PHP_EOL. '"1";"2";"3"' . PHP_EOL . '"4";"5";"6"' . PHP_EOL, "UCS-2LE", "auto"),
            CSVHelper::build([], [[1, 2, 3], [4, 5, 6]])
        );
    }

    public function testBuildWithColumns(): void
    {
        $this->assertSame(
            chr(255) . chr(254) . mb_convert_encoding('"a";"b";"c"'. PHP_EOL. '"1";"2";"3"' . PHP_EOL . '"4";"5";"6"' . PHP_EOL, "UCS-2LE", "auto"),
            CSVHelper::build(['a', 'b', 'c'], [[1, 2, 3], [4, 5, 6]])
        );
    }

    public function testBuildWithFunctions(): void
    {
        $this->assertSame(
            chr(255) . chr(254) . mb_convert_encoding('"a";"b";"c"'. PHP_EOL. '"1";"2";"3"' . PHP_EOL . '"4";"5";"6"' . PHP_EOL, "UCS-2LE", "auto"),
            CSVHelper::build(['a', 'b', 'c'], [[1, 2, 3], [4, 5, 6]], ['a' => function ($value) {
                return $value;
            }])
        );
    }

    /*public function testBuildWithIgnore(): void
    {
        $this->assertSame(
            chr(255) . chr(254) . mb_convert_encoding('"b";"c"'. PHP_EOL. '"2";"3"' . PHP_EOL . '"5";"6"' . PHP_EOL, "UCS-2LE", "auto"),
            CSVHelper::build(['a', 'b', 'c'], [[1, 2, 3], [4, 5, 6]], [], ['a'])
        );
    }*/
}
