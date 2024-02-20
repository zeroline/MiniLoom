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
use zeroline\MiniLoom\Data\Database\SQL\DatabaseAbstractionModel;

use zeroline\MiniLoom\Modules\JobManagement\Service\JobConsumerService;

class JobHistoryModel extends DatabaseAbstractionModel
{
    /**
     *
     * @var string
     */
    protected static string $tableName = "jobHistory";

    /**
     *
     * @var null|JobModel
     */
    private ?JobModel $cachedJob = null;

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
     *
     * @var array<string, array<string, array<mixed>>>
     */
    protected array $fieldsForValidation = array(
        'jobId' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::IS_NUMBER => array(),
        ),
    );

    /**
     *
     * @return int
     */
    public function getJobId(): int
    {
        return $this->jobId;
    }

    /**
     *
     * @return JobModel
     */
    public function getJob(): JobModel
    {
        if (is_null($this->cachedJob)) {
            $result = JobConsumerService::getJobById($this->getJobId());
            if ($result instanceof JobModel) {
                $this->cachedJob = $result;
            } else {
                throw new RuntimeException("Job not found");
            }
        }
        return $this->cachedJob;
    }

    /**
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     *
     * @return string
     */
    public function getAdditionalData(): string
    {
        return $this->additionalData;
    }

    /**
     *
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }
}
