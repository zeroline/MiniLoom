<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 * Test case for JWT and the JWTManager
 */

namespace zeroline\MiniLoom\Tests\Security;

use Exception;
use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\Security\JWT\JWT as JWT;
use zeroline\MiniLoom\Security\JWT\JWTManager as JWTManager;

class JWTTest extends TestCase
{
    private const VALID_KEY = 'keyValid';
    private const INVALID_KEY = 'keyInvalid';


    public function testCreateJWTWithJWTManager() 
    {
        $jwt = JWTManager::createJWTWithPayload(['test' => 'test']);
        $this->assertInstanceOf(JWT::class, $jwt);
        $this->assertNotEmpty($jwt);
    }

    public function testPayloadAccessToJWT() 
    {
        $jwt = JWTManager::createJWTWithPayload(['test' => 'test']);
        $this->assertEquals('test', $jwt->getPayload()->test);
        $this->assertEquals('test', $jwt->test);
    }

    public function testSigningJWT() 
    {
        $jwt = JWTManager::createJWTWithPayload(['test' => 'test']);
        $jwtString = JWTManager::stringFromJWT($jwt, self::VALID_KEY);
        $jwtFromStringWithValidKey = JWTManager::jwtFromString($jwtString, self::VALID_KEY);

        $this->assertEquals('test', $jwtFromStringWithValidKey->getPayload()->test);

        $this->expectException(Exception::class);
        $jwtFromStringWithInvalidKey = JWTManager::jwtFromString($jwtString, self::INVALID_KEY);
        $this->assertNull($jwtFromStringWithInvalidKey);
    }

    public function testInvalidNotBefore()
    {
        $jwt = JWTManager::createJWTWithPayload(['test' => 'test']);
        $jwt->setNotBefore(time() + 1000);
        $jwtString = JWTManager::stringFromJWT($jwt, self::VALID_KEY);

        $this->expectException(Exception::class);
        $jwtFromStringWithValidKey = JWTManager::jwtFromString($jwtString, self::VALID_KEY);
    }

    public function testValidNotBefore() 
    {
        $jwt = JWTManager::createJWTWithPayload(['test' => 'test']);
        $jwt->setNotBefore(time() - 1000);
        $jwtString = JWTManager::stringFromJWT($jwt, self::VALID_KEY);
        $jwtFromStringWithValidKey = JWTManager::jwtFromString($jwtString, self::VALID_KEY);

        $this->assertEquals('test', $jwtFromStringWithValidKey->getPayload()->test);
    }

    public function testValidIssuedAt() 
    {
        $jwt = JWTManager::createJWTWithPayload(['test' => 'test']);
        $jwt->setIssuedAt(time() - 1000);
        $jwtString = JWTManager::stringFromJWT($jwt, self::VALID_KEY);
        $jwtFromStringWithValidKey = JWTManager::jwtFromString($jwtString, self::VALID_KEY);

        $this->assertEquals('test', $jwtFromStringWithValidKey->getPayload()->test);
    }

    public function testInvalidIssuedAt() 
    {
        $jwt = JWTManager::createJWTWithPayload(['test' => 'test']);
        $jwt->setIssuedAt(time() + 1000);
        $jwtString = JWTManager::stringFromJWT($jwt, self::VALID_KEY);

        $this->expectException(Exception::class);
        $jwtFromStringWithValidKey = JWTManager::jwtFromString($jwtString, self::VALID_KEY);
    }

    public function testActuallyExpired()
    {
        $jwt = JWTManager::createJWTWithPayload(['test' => 'test']);
        $jwt->setExpired(time() - 1000);
        $jwtString = JWTManager::stringFromJWT($jwt, self::VALID_KEY);

        $this->expectException(Exception::class);
        $jwtFromStringWithValidKey = JWTManager::jwtFromString($jwtString, self::VALID_KEY);
    }

    public function testJWTIsNotExpired()
    {
        $jwt = JWTManager::createJWTWithPayload(['test' => 'test']);
        $jwt->setExpired(time() + 1000);
        $jwtString = JWTManager::stringFromJWT($jwt, self::VALID_KEY);
        $jwtFromStringWithValidKey = JWTManager::jwtFromString($jwtString, self::VALID_KEY);

        $this->assertEquals('test', $jwtFromStringWithValidKey->getPayload()->test);
    }

    public function testInvalidJWTString()
    {
        $this->expectException(Exception::class);
        $jwtFromStringWithValidKey = JWTManager::jwtFromString('invalid', self::VALID_KEY);
    }

}