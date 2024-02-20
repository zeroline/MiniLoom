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
use zeroline\MiniLoom\Modules\JobManagement\Model\JobTypeModel;

final class JobTypeService
{
    /**
     *
     * @param string $name
     * @param int $mode
     * @param string $locator
     * @param int $maxRetries
     * @param int $retryDelay
     * @param null|string $description
     * @param array<mixed> $configuration
     * @return null|JobTypeModel
     * @throws RuntimeException
     * @throws PDOException
     */
    public static function addJobType(string $name, int $mode, string $locator, int $maxRetries = JobTypeModel::DEFAULT_MAX_RETRIES, int $retryDelay = JobTypeModel::DEFAULT_RETRY_DELAY, ?string $description = null, array $configuration = array()): ?JobTypeModel
    {
        $model = new JobTypeModel([
            'name' => $name,
            'mode' => $mode,
            'locator' => $locator,
            'description' => $description,
            'configuration' => json_encode($configuration),
            'maxRetries' => $maxRetries,
            'retryDelay' => $retryDelay
        ]);

        if ($model->validateAndSave()) {
            return $model;
        }
        return null;
    }

    /**
     * Find one jobType by it's id
     *
     * @param integer $id
     * @return JobTypeModel|null
     */
    public static function getJobTypeById(int $id): ?JobTypeModel
    {
        $result = JobTypeModel::findOneById($id);
        if($result instanceof JobTypeModel) {
            return $result;
        }
        return null;
    }

    /**
     * Find one jobType by its name
     *
     * @param string $name
     * @return JobTypeModel|null
     */
    public static function getJobTypeByName(string $name): ?JobTypeModel
    {
        return JobTypeModel::repository()->where('name', $name)->readOne();
    }
}
