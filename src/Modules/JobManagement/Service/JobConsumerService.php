<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\JobManagement
 *
 */

namespace zeroline\MiniLoom\Modules\JobManagement\Service;

use zeroline\MiniLoom\Modules\JobManagement\Model\JobModel;
use zeroline\MiniLoom\Modules\JobManagement\Model\JobTypeModel;
use zeroline\MiniLoom\Modules\JobManagement\Model\JobForProcessingModel;

use zeroline\MiniLoom\Modules\JobManagement\Lib\JobHandlingResultStatus;

use zeroline\MiniLoom\Modules\JobManagement\Service\JobHistoryService;
use zeroline\MiniLoom\Modules\JobManagement\Service\JobProducerService;

use Exception;
use RuntimeException;
use PDOException;

final class JobConsumerService
{
    /**
     * Finds on job model by its id
     *
     * @param int $id
     * @return null|JobModel
     */
    public static function getJobById(int $id): ?JobModel
    {
        $result = JobModel::findOneById($id);
        if ($result instanceof JobModel) {
            return $result;
        }
        return null;
    }

    /**
     * Returns an array of @see JobForProcessingModel
     * @param JobTypeModel $type
     * @param int $limit
     * @return array<JobForProcessingModel>
     * @throws RuntimeException
     * @throws PDOException
     */
    public static function getJobsForProcessingByType(JobTypeModel $type, int $limit): array
    {
        return JobForProcessingModel::repository()->where('jobType', $type->getId())->limit($limit)->read();
    }

    /**
     * Returns a job for handling
     * @param JobModel $job
     * @return null|JobModel
     * @throws PDOException
     * @throws RuntimeException
     */
    public static function getJobForHandling(JobModel $job): ?JobModel
    {
        if ($job->isOpen() && $job->hasAttemptsLeft()) {
            $job->status = JobModel::STATUS_PROCESSING;
            $job->incAttempt();
            $job->save();

            JobHistoryService::log($job, JobHistoryService::MESSAGE_RETURNED_FOR_HANDLING, null);

            return $job;
        }

        return null;
    }

    /**
     * Use this only if the processing is interupted.
     * It reduces the attempt counter by one and sets the job to open.
     *
     * @param JobModel $jobModel
     * @return void
     */
    public static function resetJobToOpenByGracefullyShutdown(JobModel $jobModel): void
    {
        $jobModel->attempt = $jobModel->attempt - 1;
        $jobModel->status = JobModel::STATUS_OPEN;
        $jobModel->save();
    }

    /**
     * Creates a new job with the data of the given job
     *
     * @param JobModel $jobModel
     * @return void
     */
    public static function cloneToRestartJob(JobModel $jobModel): void
    {
        JobProducerService::addJob($jobModel->getType(), $jobModel->getPayloadArray(), $jobModel->getParameterArray());
    }

    /**
     * Processes the handlers result after processing the job
     *
     * @param JobModel $jobModel
     * @param integer $result
     * @param string|null $message
     * @param mixed $additionalData
     * @return void
     */
    public static function processJobHandlingResult(JobModel $jobModel, int $result, ?string $message = null, $additionalData = null): void
    {
        switch ($result) {
            case JobHandlingResultStatus::SUCCESS:
                $jobModel->status = JobModel::STATUS_FINISHED;
                $jobModel->save();
                JobHistoryService::log($jobModel, JobHistoryService::MESSAGE_SUCCESS . ($message ? ' : ' . $message : ''), $additionalData);
                break;
            case JobHandlingResultStatus::FAILED:
                if ($jobModel->hasAttemptsLeft()) {
                    $jobModel->status = JobModel::STATUS_OPEN;
                    JobHistoryService::log($jobModel, JobHistoryService::MESSAGE_FAILED_BUT_RETRY . ($message ? ' : ' . $message : ''), $additionalData);
                } else {
                    $jobModel->status = JobModel::STATUS_FAILED;
                    JobHistoryService::log($jobModel, JobHistoryService::MESSAGE_FAILED . ($message ? ' : ' . $message : ''), $additionalData);
                }
                $jobModel->save();
                break;
            case JobHandlingResultStatus::ERROR:
                $jobModel->status = JobModel::STATUS_ERROR;
                $jobModel->save();
                JobHistoryService::log($jobModel, JobHistoryService::MESSAGE_ERROR . ($message ? ' : ' . $message : ''), $additionalData);
                break;
            default:
                throw new Exception('Invalid job handling result');
        }
    }
}
