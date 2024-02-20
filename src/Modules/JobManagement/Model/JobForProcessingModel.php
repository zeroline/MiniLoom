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
use zeroline\MiniLoom\Data\Database\SQL\DatabaseAbstractionModel;
use zeroline\MiniLoom\Modules\JobManagement\Model\JobModel;

use zeroline\MiniLoom\Modules\JobManagement\Service\JobConsumerService;

class JobForProcessingModel extends DatabaseAbstractionModel
{
    /**
     *
     * @var string
     */
    protected static string $tableName = "vJobsForProcessing";

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
     * @return null|JobModel
     */
    public function getJob(): ?JobModel
    {
        $result = JobConsumerService::getJobById($this->jobId);
        if ($result instanceof JobModel) {
            return $result;
        }
        return null;
    }
}
