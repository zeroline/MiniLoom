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
use zeroline\MiniLoom\Modules\JobManagement\Lib\JobHandlingResult;

interface IJobWorker
{
    public function handleJob(JobModel $job): JobHandlingResult;
}
