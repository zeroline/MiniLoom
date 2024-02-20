<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\JobManagement
 *
 */

namespace zeroline\MiniLoom\Modules\JobManagement\Commands;

use RuntimeException;
use PDOException;
use zeroline\MiniLoom\Controlling\CLI\Controller;
use zeroline\MiniLoom\Modules\JobManagement\Model\JobModel;
use zeroline\MiniLoom\Modules\JobManagement\Service\JobTypeService;
use zeroline\MiniLoom\Modules\JobManagement\Service\JobConsumerService;

class ManagerCommandController extends Controller
{
    private const LINE = '================================';

    /**
     *
     * @param int $status
     * @return string
     */
    private function getJobStatusString(int $status): string
    {
        switch ($status) {
            case JobModel::STATUS_OPEN:
                return 'Open';
            case JobModel::STATUS_PROCESSING:
                return 'Processing';
            case JobModel::STATUS_FINISHED:
                return 'Finished';
            case JobModel::STATUS_FAILED:
                return 'Failed';
            case JobModel::STATUS_ERROR:
                return 'Error';
            default:
                return 'Unknown';
        }
    }

    /**
     *
     * @param int $jobTypeId
     * @param int $limit
     * @return void
     * @throws RuntimeException
     * @throws PDOException
     */
    public function list(int $jobTypeId, int $limit = 10): void
    {
        $jobType = JobTypeService::getJobTypeById($jobTypeId);
        if ($jobType) {
            $jobsForProcessing = JobConsumerService::getJobsForProcessingByType($jobType, $limit);
            foreach ($jobsForProcessing as $jobForProcessing) {
                $job = $jobForProcessing->getJob();
                if ($job) {
                    $this->outLine(self::LINE);
                    $this->outLine($jobType->getName() . ' (' . $this->getJobStatusString($job->getStatus()) . ')');
                    $this->outLine($job->payload);
                    $this->outLine(self::LINE . PHP_EOL);
                }
            }
        } else {
            $this->outLine('Invalid job type');
        }
    }

    public function listTypes(): void
    {
    }
}
