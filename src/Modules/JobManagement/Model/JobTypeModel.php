<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\JobManagement
 *
 */

namespace zeroline\MiniLoom\Modules\JobManagement\Model;

use zeroline\MiniLoom\Data\Validation\ValidatorRule;
use zeroline\MiniLoom\Data\Database\SQL\DatabaseAbstractionModel;

use Exception;
use ReflectionException;
use RuntimeException;

class JobTypeModel extends DatabaseAbstractionModel
{
    public const MODE_PHP_HANDLER = 100;
    public const MODE_PHP_HANDLER_INFINITE = 101;

    public const DEFAULT_MAX_RETRIES = 5;
    public const DEFAULT_RETRY_DELAY = 10; // 10 seconds delay before trying again

    /**
     *
     * @var string
     */
    protected static string $tableName = "jobType";

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
        'name' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::STR_MAX => array(255),
        ),
        'mode' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::IS_NUMBER => array(),
            ValidatorRule::IN_ARRAY => array(
                array(self::MODE_PHP_HANDLER, self::MODE_PHP_HANDLER_INFINITE)
            )
        ),
        'configuration' => array(
        ),
        'locator' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::STR_MAX => array(16000),
        ),
        'retryDelay' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::IS_NUMBER => array(),
        ),
        'maxRetries' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::IS_NUMBER => array(),
        )
    );

    /**
     *
     * @return string
     */
    public function getConfiguration(): string
    {
        return $this->configuration;
    }

    /**
     *
     * @return mixed
     */
    public function getConfigurationObject()
    {
        return json_decode($this->getConfiguration());
    }

    /**
     *
     * @param mixed $config
     * @return void
     * @throws Exception
     */
    public function setConfiguration(mixed $config): void
    {
        if (!is_array($config) && !is_object($config)) {
            throw new Exception('Invalid format');
        }
        $this->configuration = json_encode($config);
    }

    /**
     *
     * @return null|int
     */
    public function getRetryDelay(): ?int
    {
        return $this->retryDelay;
    }

    /**
     *
     * @return null|int
     */
    public function getMaxRetries(): ?int
    {
        return $this->maxRetries;
    }

    /**
     *
     * @return string
     */
    public function getLocator(): string
    {
        return $this->locator;
    }

    /**
     *
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }

    /**
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
