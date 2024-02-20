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
use zeroline\MiniLoom\Modules\JobManagement\Model\JobHistoryModel;

final class JobHistoryService
{
    public const MESSAGE_CREATED = "The job has been created";
    public const MESSAGE_RETURNED_FOR_HANDLING = "The job has been selected for handling";
    public const MESSAGE_SUCCESS = 'Job completed successfully';
    public const MESSAGE_FAILED = 'Job failed';
    public const MESSAGE_FAILED_BUT_RETRY = 'Job failed, retrys left';
    public const MESSAGE_ERROR = 'Job raised an error';

    /**
     *
     * @param JobModel $job
     * @param string $message
     * @param mixed $additionalData
     * @return null|JobHistoryModel
     * @throws RuntimeException
     * @throws PDOException
     */
    public static function log(JobModel $job, string $message, $additionalData): ?JobHistoryModel
    {
        $model = new JobHistoryModel([
            'jobId' => $job->getId(),
            'message' => $message,
            'additionalData' => is_string($additionalData) ? $additionalData : json_encode($additionalData),
            'created' => date('Y-m-d H:i:s')
        ]);

        if ($model->validateAndSave()) {
            return $model;
        }
        return null;
    }
}
