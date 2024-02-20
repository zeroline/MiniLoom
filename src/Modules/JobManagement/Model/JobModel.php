<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\JobManagement
 *
 */

namespace zeroline\MiniLoom\Modules\JobManagement\Model;

use ReflectionException;
use RuntimeException;
use zeroline\MiniLoom\Data\Validation\ValidatorRule;
use zeroline\MiniLoom\Modules\DataIntegrity\Model\DataIntegrityModel;

class JobModel extends DataIntegrityModel
{
    public const STATUS_OPEN = 0;
    public const STATUS_PROCESSING = 100;
    public const STATUS_FAILED = 400;
    public const STATUS_ERROR = 666;
    public const STATUS_FINISHED = 500;

    /**
     *
     * @var string
     */
    protected static string $tableName = "job";

    /**
     *
     * @var null|JobTypeModel
     */
    private ?JobTypeModel $cachedType = null;

    /**
     *
     * @param array<string, mixed>|object $data
     * @return void
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function __construct(array|object $data = array())
    {
        parent::__construct($data);
    }

    /**
     * @var array<string, array<string, array<mixed>>>
     */
    protected array $fieldsForValidation = array(
        'type' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::IS_NUMBER => array(),
        ),
        'payload' => array(
            ValidatorRule::REQUIRED => array(),
        ),
        'parameter' => array(
            ValidatorRule::REQUIRED => array(),
        ),
        'status' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::IS_NUMBER => array(),
            ValidatorRule::IN_ARRAY => array(
                array(self::STATUS_OPEN, self::STATUS_PROCESSING, self::STATUS_ERROR, self::STATUS_FINISHED, self::STATUS_FAILED)
            ),
        ),
        'attempt' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::IS_NUMBER => array(),
        ),
    );

    /**
     *
     * @return int
     */
    public function getTypeId(): int
    {
        return $this->type;
    }

    /**
     *
     * @return JobTypeModel
     */
    public function getType(): JobTypeModel
    {
        if (is_null($this->cachedType)) {
            $result = JobTypeModel::findOneById($this->getTypeId());
            if ($result instanceof JobTypeModel) {
                $this->cachedType = $result;
            } else {
                throw new RuntimeException("JobType not found");
            }
        }
        return $this->cachedType;
    }

    /**
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     *
     * @return int
     */
    public function getAttempt(): int
    {
        return $this->attempt;
    }

    /**
     *
     * @param int $attempt
     * @return void
     */
    public function setAttempt(int $attempt): void
    {
        $this->attempt = $attempt;
    }

    /**
     *
     * @return int
     */
    public function incAttempt(): int
    {
        $this->attempt++;
        return $this->getAttempt();
    }

    /**
     *
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->getStatus() === self::STATUS_OPEN;
    }

    /**
     *
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->getStatus() === self::STATUS_FINISHED;
    }

    /**
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->getStatus() === self::STATUS_FAILED;
    }

    /**
     *
     * @return bool
     */
    public function isProcessing(): bool
    {
        return $this->getStatus() === self::STATUS_PROCESSING;
    }

    /**
     *
     * @return bool
     */
    public function hasAttemptsLeft(): bool
    {
        return ( $this->getAttempt() < $this->getType()->getMaxRetries() );
    }

    /**
     *
     * @return string
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    /**
     *
     * @return array<mixed>
     */
    public function getPayloadArray(): array
    {
        return json_decode($this->getPayload());
    }

    /**
     *
     * @return string
     */
    public function getParameter(): string
    {
        return $this->parameter;
    }

    /**
     *
     * @return array<mixed>
     */
    public function getParameterArray(): array
    {
        return json_decode($this->getParameter());
    }
}
