<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\JobManagement
 *
 */

namespace zeroline\MiniLoom\Modules\JobManagement\Lib;

use zeroline\MiniLoom\Modules\JobManagement\Model\JobModel;

class JobHandlingResult
{
    private int $resultCode;
    private JobModel $job;
    private ?string $message = null;
    private mixed $additionalData = null;

    /**
     *
     * @param JobModel $jobModel
     * @param int $resultCode
     * @param null|string $message
     * @param mixed $additionalData
     * @return void
     */
    public function __construct(JobModel $jobModel, int $resultCode, ?string $message = null, mixed $additionalData = null)
    {
        $this->job = $jobModel;
        $this->resultCode = $resultCode;
        $this->message = $message;
        $this->additionalData = $additionalData;
    }

    /**
     *
     * @return JobModel
     */
    public function getJob(): JobModel
    {
        return $this->job;
    }

    /**
     *
     * @return int
     */
    public function getResultCode(): int
    {
        return $this->resultCode;
    }

    /**
     *
     * @return null|string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     *
     * @return mixed
     */
    public function getAdditionalData() : mixed
    {
        return $this->additionalData;
    }
}
