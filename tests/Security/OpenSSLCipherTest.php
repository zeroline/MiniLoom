<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 * Test case for HMAC
 */

namespace zeroline\MiniLoom\Tests\Security;

use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\Security\Encryption\OpenSSL\Crypter as Crypter;

class OpenSSLCipherTest extends TestCase
{
    private const MESSAGE =
        'MiniLoom says: Hello World! Lorem ipsum dolor sit amet,'.
        'consetetur sadipscing elitr, sed diam nonumy eirmod tempor'.
        'invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua '.
        'At vero eos et accusam et justo duo dolores et ea rebum. Stet clita '.
        'kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';
    private const KEY = '1234567890';
    private const INVALID_KEY = '0123456789';

    public function testEncryptingAndDecrypting()
    {
        $encryptedMessage = Crypter::encrypt(self::MESSAGE, self::KEY);
        $this->assertNotEmpty($encryptedMessage);
        $this->assertNotEquals(self::MESSAGE, $encryptedMessage);

        $decryptedMessage = Crypter::decrypt($encryptedMessage, self::KEY);
        $this->assertNotEmpty($decryptedMessage);
        $this->assertEquals(self::MESSAGE, $decryptedMessage);
    }

    public function testEncryptingAndDecryptingWithInvalidKey()
    {
        $encryptedMessage = Crypter::encrypt(self::MESSAGE, self::KEY);
        $this->assertNotEmpty($encryptedMessage);
        $this->assertNotEquals(self::MESSAGE, $encryptedMessage);

        $decryptedMessage = Crypter::decrypt($encryptedMessage, self::INVALID_KEY);
        $this->assertEmpty($decryptedMessage);
        $this->assertFalse($decryptedMessage);
        $this->assertNotEquals(self::MESSAGE, $decryptedMessage);
    }

    public function testEncryptingAndDecryptingWithInvalidTag()
    {
        $encryptedMessage = Crypter::encrypt(self::MESSAGE, self::KEY);
        $this->assertNotEmpty($encryptedMessage);
        $this->assertNotEquals(self::MESSAGE, $encryptedMessage);

        $decryptedMessage = Crypter::decrypt($encryptedMessage, self::KEY);
        $this->assertNotEmpty($decryptedMessage);
        $this->assertEquals(self::MESSAGE, $decryptedMessage);

        $decryptedMessage = Crypter::decrypt($encryptedMessage . 'invalid', self::KEY);
        $this->assertEmpty($decryptedMessage);
        $this->assertFalse($decryptedMessage);
        $this->assertNotEquals(self::MESSAGE, $decryptedMessage);
    }

    public function testEncryptingAndDecryptingWithInvalidMessage()
    {
        $encryptedMessage = Crypter::encrypt(self::MESSAGE, self::KEY);
        $this->assertNotEmpty($encryptedMessage);
        $this->assertNotEquals(self::MESSAGE, $encryptedMessage);

        $decryptedMessage = Crypter::decrypt($encryptedMessage, self::KEY);
        $this->assertNotEmpty($decryptedMessage);
        $this->assertEquals(self::MESSAGE, $decryptedMessage);

        $decryptedMessage = Crypter::decrypt('invalid'.$encryptedMessage, self::KEY);
        $this->assertEmpty($decryptedMessage);
        $this->assertFalse($decryptedMessage);
        $this->assertNotEquals(self::MESSAGE, $decryptedMessage);
    }
}
