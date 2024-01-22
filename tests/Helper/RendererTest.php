<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 * Test case for Renderer
 */

namespace zeroline\MiniLoom\Tests\Helper;

use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\Helper\Renderer;

final class RendererTest extends TestCase
{
    const FILENAME = 'tests/assets/Renderer/template.test.php';
    const HTML = '<html><body><h1>Test</h1></body></html>';
    const DATA = [
        'test' => 'test from data',
        'test2' => 'test2 from data'
    ];
    const INTERPOLATE_STRING = 'test {{ test }} {{ test2 }}';

    public function testRenderFile(): void
    {
        $this->assertStringContainsString(
            'Test case for Renderer',
            Renderer::renderFile(self::FILENAME, [])
        );
    }

    public function testRenderHtml(): void
    {
        $this->assertStringContainsString(
            '<h1>Test</h1>',
            Renderer::renderHtml(self::HTML, self::DATA)
        );
    }

    public function testRenderFileWithData(): void
    {
        $this->assertStringContainsString(
            self::DATA['test'],
            Renderer::renderFile(self::FILENAME, self::DATA)
        );
    }

    public function testInterpolate(): void
    {
        $interpolated = Renderer::interpolate(self::INTERPOLATE_STRING, self::DATA);
        $this->assertStringContainsString(
            self::DATA['test'],
            $interpolated
        );

        $this->assertStringContainsString(
            self::DATA['test2'],
            $interpolated
        );
    }
}
