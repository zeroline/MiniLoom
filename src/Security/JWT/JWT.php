<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Security
 *
 * The JWT class represents a JSON Web Token.
 */

namespace zeroline\MiniLoom\Security\JWT;

use zeroline\MiniLoom\Data\DataContainer as DataContainer;

class JWT
{
    private const HEADER_TYPE = "typ";
    private const HEADER_ALGORITHM = "alg";
    public const TYPE = "JWT";

    /**
     *
     * @var array<string, string>
     */
    private array $header = array();
    private DataContainer $payload;

    /**
     * Constructs a new object
     *
     * @param string $algorithm
     * @param array<string, mixed>|object $payload
     */
    public function __construct(string $algorithm, array|object $payload)
    {
        $this->header = array(
            'typ' => self::TYPE,
            'alg' => $algorithm
        );
        $this->payload = new DataContainer($payload);
    }

    /**
     * Returns the token algorithm
     *
     * @return string
     */
    public function getAlgorithm(): string
    {
        return $this->header[self::HEADER_ALGORITHM];
    }

    /**
     * Returns the token type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->header[self::HEADER_TYPE];
    }

    /**
     *
     * @param DataContainer $payload
     * @return void
     */
    public function setPayload(DataContainer $payload): void
    {
        $this->payload = $payload;
    }

    /**
     *
     * @return DataContainer
     */
    public function getPayload() : DataContainer
    {
        return $this->payload;
    }

    /**
     * Set not before timestamp in payload
     *
     * @param integer $notBefore
     * @return void
     */
    public function setNotBefore(int $notBefore): void
    {
        $this->getPayload()->nbf = $notBefore;
    }

    /**
     * Returns not before timestamp if available
     *
     * @return integer|null
     */
    public function getNotBefore(): ?int
    {
        return intval($this->getPayload()->nbf);
    }

    /**
     * Set issued at timestamo in payload
     *
     * @param integer $issuedAt
     * @return void
     */
    public function setIssuedAt(int $issuedAt): void
    {
        $this->getPayload()->iat = $issuedAt;
    }

    /**
     * Returns issued at timestamp if available
     *
     * @return integer|null
     */
    public function getIssuedAt(): ?int
    {
        return $this->getPayload()->iat;
    }

    /**
     * Set expired timestamp in payload
     *
     * @param integer $expired
     * @return void
     */
    public function setExpired(int $expired): void
    {
        $this->getPayload()->exp = $expired;
    }

    /**
     * Returns expired timestamp if available
     *
     * @return integer|null
     */
    public function getExpired(): ?int
    {
        return $this->getPayload()->exp;
    }

    /**
     * Set issuer string in payload
     *
     * @param string $issuer
     * @return void
     */
    public function setIssuer(string $issuer): void
    {
        $this->getPayload()->iss = $issuer;
    }

    /**
     * Returns issuer string if available
     *
     * @return string|null
     */
    public function getIssuer(): ?string
    {
        return $this->getPayload()->iss;
    }

    /**
     * Set subject string in payload
     *
     * @param string $subject
     * @return void
     */
    public function setSubject(string $subject): void
    {
        $this->getPayload()->sub = $subject;
    }

    /**
     * Returns subject string if available
     *
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->getPayload()->sub;
    }

    /**
     * Set audience string in payload
     *
     * @param string $audience
     * @return void
     */
    public function setAudience(string $audience): void
    {
        $this->getPayload()->aud = $audience;
    }

    /**
     * Returns audience string if available
     *
     * @return string|null
     */
    public function getAudience(): ?string
    {
        return $this->getPayload()->aud;
    }

    /**
     * Set identified by string in payload
     *
     * @param string $identifiedBy
     * @return void
     */
    public function setIdentifiedBy(string $identifiedBy): void
    {
        $this->getPayload()->jti = $identifiedBy;
    }

    /**
     * Returns the identified by string if available
     *
     * @return string|null
     */
    public function getIdentifiedBy(): ?string
    {
        return $this->getPayload()->jti;
    }

    /**
     * Override magic method to receive more payload data.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getPayload()->{$key};
    }

    /**
     * Override magic method to receive more payload data.
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->getPayload()->{$key} = $value;
    }

    /**
     * Override magic method to check existens of payload attributes
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->getPayload()->{$key});
    }
}
