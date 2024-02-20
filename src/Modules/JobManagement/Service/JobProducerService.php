<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\JobManagement
 *
 */

namespace zeroline\MiniLoom\Modules\JobManagement\Service;

use RuntimeException;
use PDOException;
use zeroline\MiniLoom\Modules\JobManagement\Model\JobModel;
use zeroline\MiniLoom\Modules\JobManagement\Model\JobTypeModel;

use zeroline\MiniLoom\Modules\DataIntegrity\Lib\EntryState;

use zeroline\MiniLoom\Modules\JobManagement\Service\JobHistoryService;

final class JobProducerService
{

    /**
     * Add a new job to the queue
     * @param JobTypeModel $jobType
     * @param array<mixed> $payload
     * @param array<mixed> $parameter
     * @return null|JobModel
     * @throws RuntimeException
     * @throws PDOException
     */
    public static function addJob(JobTypeModel $jobType, array $payload = [], array $parameter = []): ?JobModel
    {
        $model = new JobModel([
            'type' => $jobType->getId(),
            'payload' => json_encode($payload),
            'parameter' => json_encode($parameter),
            'status' => JobModel::STATUS_OPEN,
            'attempt' => 0,
            'activeState' => EntryState::ACTIVE
        ]);

        if ($model->validateAndSave()) {
            JobHistoryService::log($model, JobHistoryService::MESSAGE_CREATED, null);
            return $model;
        }
        return null;
    }
}
