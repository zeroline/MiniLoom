<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 * Test case for EmbedImageHelper
 */

namespace zeroline\MiniLoom\Tests\Helper;

use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\Helper\EmbedImageHelper;

final class EmbedImageHelperTest extends TestCase
{
    const FILENAME = 'tests/assets/EmbedImageHelper/testPNGImage.png';

    public function testGenerateBase64ImageDataAndMimeFromFile(): void
    {
        list($base64data, $mimeType) = EmbedImageHelper::generateBase64ImageDataAndMimeFromFile(self::FILENAME);
        $this->assertStringContainsString(
            'data:image/png;base64,',
            EmbedImageHelper::generateImageSourceStringFromData($base64data, $mimeType)
        );
    }

    public function testGenerateImageSourceStringFromFile(): void
    {
        $this->assertStringContainsString(
            'data:image/png;base64,',
            EmbedImageHelper::generateImageSourceStringFromFile(self::FILENAME)
        );
    }

    public function testForValidBase64String() : void
    {
        list($base64data, $mimeType) = EmbedImageHelper::generateBase64ImageDataAndMimeFromFile(self::FILENAME);
        $this->assertTrue((bool)preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $base64data));
    }

    public function testForValidContentAndMimeType() : void
    {
        list($base64data, $mimeType) = EmbedImageHelper::generateBase64ImageDataAndMimeFromFile(self::FILENAME);
        $this->assertNotEmpty($base64data);
        $this->assertNotEmpty($mimeType);
    }
}
