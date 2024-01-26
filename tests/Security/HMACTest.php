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
use zeroline\MiniLoom\Security\HMAC as HMAC;

class HMACTest extends TestCase
{
    private const MESSAGE = 'MiniLoom says: Hello World!';
    private const KEY = '1234567890';
    private const INVALID_KEY = '0123456789';
    private const ALTERNATIVE_ALGORITHM = 'sha256';

    public function testValidAlgorithm()
    {
        $this->assertTrue(HMAC::isAlgorithmSupported('sha512')); // (should) exist
        $this->assertFalse(HMAC::isAlgorithmSupported('sha513')); // does not exist
    }

    public function testSigningWithDefaultAlgorithm()
    {
        $signedMessage = HMAC::sign(self::MESSAGE, self::KEY);
        $this->assertNotEmpty($signedMessage);
        $this->assertNotEquals(self::MESSAGE, $signedMessage);
    }

    public function testSigningWithAlternativeAlgorithm()
    {
        $signedMessage = HMAC::sign(self::MESSAGE, self::KEY, self::ALTERNATIVE_ALGORITHM);
        $this->assertNotEmpty($signedMessage);
        $this->assertNotEquals(self::MESSAGE, $signedMessage);
    }

    public function testSigningAndVerifiyingWithDefaultAlgorithm()
    {
        $signedMessage_one = HMAC::sign(self::MESSAGE, self::KEY);
        $signedMessage_two = HMAC::sign(self::MESSAGE, self::KEY);
        $this->assertTrue(hash_equals($signedMessage_one, $signedMessage_two));
    }

    public function testSigningAndVerifiyingWithAlternativeAlgorithm()
    {
        $signedMessage_one = HMAC::sign(self::MESSAGE, self::KEY, self::ALTERNATIVE_ALGORITHM);
        $signedMessage_two = HMAC::sign(self::MESSAGE, self::KEY, self::ALTERNATIVE_ALGORITHM);
        $this->assertTrue(hash_equals($signedMessage_one, $signedMessage_two));
    }

    public function testSigningAndVerifiyingWithDefaultAlgorithmAndDifferentKeys()
    {
        $signedMessage_one = HMAC::sign(self::MESSAGE, self::KEY);
        $signedMessage_two = HMAC::sign(self::MESSAGE, self::INVALID_KEY);
        $this->assertFalse(hash_equals($signedMessage_one, $signedMessage_two));
    }
}
