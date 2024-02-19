<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\Migration
 *
 */

namespace zeroline\MiniLoom\Modules\Migration\Lib;

use Exception;
use RuntimeException;
use PDOException;
use zeroline\MiniLoom\Moduling\ModuleManager;
use zeroline\MiniLoom\Data\Database\SQL\ConnectionManager;
use zeroline\MiniLoom\Modules\Migration\Model\MigrationStatusModel;
use zeroline\MiniLoom\Data\Database\SQL\Connection;
use zeroline\MiniLoom\Data\Database\SQL\SQLFileExecuter;

final class MigrationManager
{
    private const INIT_MODULE_NAME = 'Migration';
    private const INIT_SQL_MIGRATION_FILE_NAME = '../Migrations/000000000001.sql';

    /**
     *
     * @return null|Connection
     */
    private static function getConnection(): ?Connection
    {
        return ConnectionManager::getDefaultConnection();
    }

    /**
     * Returns all found migration files for all registered modules
     *
     * @return array<string, array<string>>
     */
    public static function getMigrationFiles(): array
    {
        $result = array();
        $modules = ModuleManager::getModules();

        foreach ($modules as $module) {
            $result[$module->getModuleName()] = $module->getMigrations();
        }

        return $result;
    }

    /**
     * Returns all non migrated files ordered in modules
     *
     * @return array<string, array<string>>
     */
    public static function getNonMigratedFilesInModules(): array
    {
        $all = self::getMigrationFiles();
        $result = array();

        foreach ($all as $moduleName => $migrationFiles) {
            $result[$moduleName] = array();
            foreach ($migrationFiles as $migrationFile) {
                if (!self::hasFileBeenMigrated($moduleName, basename($migrationFile))) {
                    $result[$moduleName][] = $migrationFile;
                }
            }
        }

        foreach ($result as $moduleName => $migrationFiles) {
            if (count($migrationFiles) === 0) {
                unset($result[$moduleName]);
            } else {
                asort($result[$moduleName]);
            }
        }

        return $result;
    }

    /**
     * Checks if a table exists.
     *
     * @param string $tableName
     * @return boolean
     */
    public static function checkForTableExsistence(string $tableName): bool
    {
        $connection = static::getConnection();

        try {
            $result = $connection?->execute("SELECT 1 FROM " . $tableName . " LIMIT 1");
        } catch (Exception $ex) {
            return false;
        }
        return $result !== false;
    }

    /**
     *
     * @return boolean
     */
    public static function ensureMigrationInitialization(): bool
    {
        if (self::checkForTableExsistence(MigrationStatusModel::TABLE_NAME)) {
            return true;
        }

        try {
            $migrationFile = realpath(__DIR__.DIRECTORY_SEPARATOR.self::INIT_SQL_MIGRATION_FILE_NAME);
            if ($migrationFile === false) {
                throw new Exception("Migration file not found.");
            }
            SQLFileExecuter::loadAndExecute($migrationFile);
            $model = new MigrationStatusModel(array(
                'moduleName' => self::INIT_MODULE_NAME,
                'migrationFile' => basename($migrationFile),
                'migrationDate' => date('Y-m-d H:i:s'),
                'migrationData' => file_get_contents($migrationFile)
            ));
            if ($model->validateAndSave()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Migrates one file
     *
     * @param string $moduleName
     * @param string $migrationFile
     * @return boolean
     *
     * @throws Exception on missing tables, file or execution failures
     */
    public static function migrate(string $moduleName, string $migrationFile): bool
    {
        if (!static::ensureMigrationInitialization()) {
            throw new Exception("Migration is not possible.");
        }

        if (self::hasFileBeenMigrated($moduleName, $migrationFile)) {
            throw new Exception("File has already bin migrated.");
        }

        try {
            SQLFileExecuter::loadAndExecute($migrationFile);
            $model = new MigrationStatusModel(array(
                'moduleName' => $moduleName,
                'migrationFile' => basename($migrationFile),
                'migrationDate' => date('Y-m-d H:i:s'),
                'migrationData' => file_get_contents($migrationFile)
            ));
            if ($model->validateAndSave()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Returns the last registered migration information
     *
     * @return MigrationStatusModel|null
     *
     * @throws Exception if the migration table is missing
     */
    public static function getLastMigration(): ?MigrationStatusModel
    {
        if (!self::checkForTableExsistence(MigrationStatusModel::TABLE_NAME)) {
            throw new Exception("The migration status table is missing.");
        }
        return MigrationStatusModel::repository()->orderByDesc('migrationDate')->limit(1)->readOne();
    }

    /**
     * Checks if a migration file exists
     *
     * @param string $migrationFile
     * @return boolean
     */
    public static function migrationFileExists(string $migrationFile): bool
    {
        return file_exists($migrationFile);
    }

    /**
     *
     * @param string $moduleName
     * @param string $migrationFile
     * @return bool
     * @throws Exception
     * @throws RuntimeException
     * @throws PDOException
     */
    public static function hasFileBeenMigrated(string $moduleName, string $migrationFile): bool
    {
        if (!self::checkForTableExsistence(MigrationStatusModel::TABLE_NAME)) {
            throw new Exception("The migration status table is missing.");
        }

        return (bool)(MigrationStatusModel::repository()
        ->where('moduleName', $moduleName)
        ->where('migrationFile', $migrationFile)
        ->limit(1)
        ->count() === 1);
    }
}
