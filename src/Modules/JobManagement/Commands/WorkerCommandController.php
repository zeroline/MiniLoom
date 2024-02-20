<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\JobManagement
 *
 */

namespace zeroline\MiniLoom\Modules\JobManagement\Commands;

use Exception;
use RuntimeException;
use PDOException;
use Throwable;
use zeroline\MiniLoom\Controlling\CLI\Controller;

use zeroline\MiniLoom\Modules\JobManagement\Lib\IJobWorker;
use zeroline\MiniLoom\Modules\JobManagement\Lib\JobHandlingResultStatus;

use zeroline\MiniLoom\Modules\JobManagement\Model\JobTypeModel;

use zeroline\MiniLoom\Modules\JobManagement\Service\JobConsumerService;
use zeroline\MiniLoom\Modules\JobManagement\Service\JobTypeService;
use zeroline\MiniLoom\Modules\JobManagement\Model\JobForProcessingModel;


class WorkerCommandController extends Controller
{
    private const SLEEP_SECONDS_ON_ZERO_RESULTS = 60;

    /**
     * 
     * @var array<JobForProcessingModel>
     */
    private array $currentJobList = array();

    /**
     * 
     * @var int
     */
    private int $currentJobIndex = -1;

    /**
     * 
     * @var int
     */
    private int $currentJobListCount = 0;

    /**
     * 
     * @var int
     */
    private int $currentJobTypeId;

    /**
     * 
     * @var JobTypeModel
     */
    private JobTypeModel $currentJobType;

    /**
     * 
     * @var mixed
     */
    private $currentLimit = null;

    /**
     * 
     * @var bool
     */
    private bool $loopShouldRun = true;

    /**
     *
     * @param int $jobType
     * @param int $limit
     * @return void
     * @throws RuntimeException
     * @throws PDOException
     * @throws Throwable
     * @throws Exception
     */
    public function run(int $jobType, int $limit): void
    {
        $this->prepareSigHandling();

        $this->currentJobTypeId = $jobType;
        unset($this->currentJobType);
        $this->currentLimit = $limit;
        $this->currentJobIndex = -1;
        $this->currentJobList = array();

        $foundJobType =JobTypeService::getJobTypeById($this->currentJobTypeId);
        if ($foundJobType instanceof JobTypeModel) {
            $this->currentJobType = $foundJobType;
            $this->outLine('Job worker running for job type "' . $this->currentJobType->getName() . '", limited to ' . $this->currentLimit . ' jobs per run.');
            $this->loop($this->currentJobType, $this->currentLimit);
        } else {
            throw new Exception('Invalid job type.');
        }
        exit;
    }

    /**
     *
     * @param JobTypeModel $jobType
     * @param int $limit
     * @return void
     * @throws RuntimeException
     * @throws PDOException
     * @throws Throwable
     */
    private function loop(JobTypeModel $jobType, int $limit): void
    {
        while ($this->loopShouldRun) {
            $this->out('Searching for jobs ("' . $jobType->getName() . '")... ');

            $this->currentJobList = JobConsumerService::getJobsForProcessingByType($jobType, $limit);
            $this->currentJobListCount = count($this->currentJobList);
            $this->currentJobIndex = -1;

            $this->outLine($this->currentJobListCount . ' found');

            if ($this->currentJobListCount === 0) {
                $this->outLine('No open jobs found, I\'ll sleep for a moment (' . self::SLEEP_SECONDS_ON_ZERO_RESULTS . ' seconds)');
                sleep(self::SLEEP_SECONDS_ON_ZERO_RESULTS);
                continue;
            }

            for ($i = 0; $i < $this->currentJobListCount; $i++) {
                $this->currentJobIndex = $i;
                try {
                    $currentJob = $this->currentJobList[$this->currentJobIndex]->getJob();
                    
                    if (!$currentJob) {
                        continue;
                    }

                    $currentJob = JobConsumerService::getJobForHandling($currentJob);

                    if (!$currentJob) {
                        continue;
                    }

                    switch ($this->currentJobType->getMode()) {
                        case JobTypeModel::MODE_PHP_HANDLER:
                            $className = $this->currentJobType->getLocator();
                            $worker = new $className();
                            if ($worker instanceof IJobWorker) {
                                try {
                                    $handlingResult = $worker->handleJob($currentJob);
                                    JobConsumerService::processJobHandlingResult($currentJob, $handlingResult->getResultCode(), $handlingResult->getMessage(), $handlingResult->getAdditionalData());
                                } catch (Throwable $th) {
                                    JobConsumerService::processJobHandlingResult($currentJob, JobHandlingResultStatus::FAILED, $th->getMessage(), $th->getTrace());
                                }
                            }
                            break;
                        case JobTypeModel::MODE_PHP_HANDLER_INFINITE:
                            $className = $this->currentJobType->getLocator();
                            $worker = new $className();
                            if ($worker instanceof IJobWorker) {
                                try {
                                    $handlingResult = $worker->handleJob($currentJob);
                                    JobConsumerService::processJobHandlingResult($currentJob, $handlingResult->getResultCode(), $handlingResult->getMessage(), $handlingResult->getAdditionalData());
                                } catch (Throwable $th) {
                                    JobConsumerService::processJobHandlingResult($currentJob, JobHandlingResultStatus::FAILED, $th->getMessage(), $th->getTrace());
                                } finally {
                                    if (!$currentJob->isOpen() && !$currentJob->isProcessing()) {
                                        JobConsumerService::cloneToRestartJob($currentJob);
                                    }
                                }
                            }
                            break;
                        default:
                            throw new Exception('Implementation of job type mode is missing.');
                    }
                } catch (Throwable $th) {
                    $this->gracefullyShutdown();
                    $this->loopShouldRun = false;
                    throw $th;
                } finally {
                }
            }
        }
    }

    /**
     *
     * @return void
     * @throws PDOException
     */
    private function gracefullyShutdown(): void
    {
        $this->out('Gracefully shutdown... ');
        if ($this->currentJobIndex > -1) {
            $currentJob = $this->currentJobList[$this->currentJobIndex]->getJob();
            if($currentJob) {
                JobConsumerService::resetJobToOpenByGracefullyShutdown($currentJob);
            }            
        }
        $this->outLine('complete');
    }

    /**
     *
     * @return void
     */
    private function prepareSigHandling(): void
    {
        declare(ticks = 1);
        pcntl_async_signals(true);
        pcntl_signal(SIGTERM, array($this, "handleSignal"));
        pcntl_signal(SIGHUP, array($this, "handleSignal"));
        pcntl_signal(SIGUSR1, array($this, "handleSignal"));
    }

    /**
     * 
     * @param int $signo 
     * @param mixed $signinfo 
     * @return void 
     * @throws PDOException 
     */
    public function handleSignal(int $signo, mixed $signinfo): void
    {
        switch ($signo) {
            case SIGTERM:
                $this->outLine('Received SIGTERM, shutting down gracefully...');
                $this->gracefullyShutdown();
                $this->loopShouldRun = false;
                //exit;
                break;
            case SIGHUP:
                $this->outLine('Received SIGHUP, restarting after shutting down gracefully...');
                $this->gracefullyShutdown();
                $this->loopShouldRun = false;
                $this->run($this->currentJobTypeId, $this->currentLimit);
                break;
            case SIGUSR1:
                $this->outLine("SIGUSR1...");
                break;
            default:
                // Alle anderen Signale bearbeiten
        }
    }
}
