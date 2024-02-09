<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\Migration
 *
 */

namespace zeroline\MiniLoom\Modules\Migration\Model;

use zeroline\MiniLoom\Data\Database\SQL\DatabaseAbstractionModel;
use zeroline\MiniLoom\Data\Validation\ValidatorRule;

class MigrationStatusModel extends DatabaseAbstractionModel
{
    public const TABLE_NAME = "migrationstatus";

    /**
     *
     * @var string
     */
    protected static string $tableName = self::TABLE_NAME;

    /**
     * @var array<string, array<string, array<mixed>>>
     */
    protected array $fieldsForValidation = array(
        'moduleName' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::STR_MAX => array(255),
        ),
        'migrationFile' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::STR_MAX => array(255),
        ),
        'migrationDate' => array(
            ValidatorRule::REQUIRED => array(),
        ),
        'migrationData' => array(
            ValidatorRule::REQUIRED => array(),
        ),
    );

    /**
     * Returns the module name
     *
     * @return string
     */
    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    /**
     * Returns the executed migration file name
     *
     * @return string
     */
    public function getMigrationFile(): string
    {
        return $this->migrationFile;
    }

    /**
     * Returns the migration date
     *
     * @return string
     */
    public function getMigrationDate(): string
    {
        return $this->migrationDate;
    }

    /**
     * Returns the migration file contents
     *
     * @return string
     */
    public function getMigrationData(): string
    {
        return $this->migrationData;
    }
}
