<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Security
 *
 * The HMAC class provides a simple way to sign messages with a key.
 */

namespace zeroline\MiniLoom\Security;

final class HMAC
{
    /**
     * Default algorithm
     * Uses SHA512 as default algorithm for HMAC signing.
     * According to NIST, SHA512 is one of the most secure algorithms ( 2023 ).
     * FIPS 180-4 specifies seven hash algorithms, including SHA-1, SHA-224, SHA-256, SHA-384, SHA-512, SHA-512/224, and SHA-512/256.
     * @see https://csrc.nist.gov/projects/hash-functions
     * @hint Do not use MD5 or SHA1
     */
    public const ALGORITHM_DEFAULT = 'sha512';

    /**
     * Checks if the given algoritm is supported.
     *
     * @param string $algorithm
     * @return boolean
     */
    public static function isAlgorithmSupported(string $algorithm): bool
    {
        return in_array($algorithm, array_values(hash_hmac_algos()));
    }

    /**
     * Signs a message with the given key and algorithm
     *
     * @param string $msg
     * @param string $key
     * @param string $method
     * @return string
     *
     * @throws \Exception
     */
    public static function sign(string $msg, string $key, string $method = self::ALGORITHM_DEFAULT): string
    {
        if (!static::isAlgorithmSupported($method)) {
            throw new \Exception('Algorithm not supported');
        }
        return hash_hmac($method, $msg, $key, true);
    }
}
