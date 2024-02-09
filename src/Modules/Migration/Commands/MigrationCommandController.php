<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\Migration
 *
 */

namespace zeroline\MiniLoom\Modules\Migration\Commands;

use zeroline\MiniLoom\Controlling\CLI\Controller as CliController;
use zeroline\MiniLoom\Modules\Migration\Lib\MigrationManager;
use zeroline\MiniLoom\Modules\Migration\Model\MigrationStatusModel;

class MigrationCommandController extends CliController
{

    public function init(): void
    {
        if (MigrationManager::ensureMigrationInitialization()) {
            $this->logInfo('Init completed, migration status table exists or has been created.');
        } else {
            $this->logError('Init failed.');
        }
    }

    public function status(): void
    {
        if (!MigrationManager::checkForTableExsistence(MigrationStatusModel::TABLE_NAME)) {
            $this->logError('There is no migration status table. Please run "migration.init" first.');
            return;
        }

        $model = MigrationManager::getLastMigration();
        if ($model) {
            $this->logInfo('Last migration on ' . $model->getMigrationDate() . ' for module "' . $model->getModuleName() . '" with file "' . $model->getMigrationFile() . '"');
        } else {
            $this->logInfo('No migration information stored');
        }
    }

    public function migrateSingle(string $moduleName, string $migrationFileName): void
    {
        if (!MigrationManager::checkForTableExsistence(MigrationStatusModel::TABLE_NAME)) {
            $this->logError('There is no migration status table. Please run "migration.init" first.');
            return;
        }

        if (!MigrationManager::migrationFileExists($migrationFileName)) {
            $this->logError('Migraton file not found.');
            return;
        }

        if (MigrationManager::migrate($moduleName, $migrationFileName)) {
            $this->logInfo('Migration complete, status updated.');
        } else {
            $this->logError('Migration failed.');
        }
    }

    public function migrateAll(): void
    {
        $this->logInfo('Migrating all non migrated files.');
        if (!MigrationManager::checkForTableExsistence(MigrationStatusModel::TABLE_NAME)) {
            $this->logError('There is no migration status table. Please run "Core Migration init" first.');
            return;
        }

        $all = MigrationManager::getNonMigratedFilesInModules();
        $allMigrationFiles = [];
        foreach ($all as $moduleName => $migrationFiles) {
            foreach ($migrationFiles as $migrationFile) {
                $allMigrationFiles[$migrationFile] = $moduleName;
            }
        }
        ksort($allMigrationFiles);
        foreach ($allMigrationFiles as $migrationFile => $moduleName) {
            $this->logInfo('Migrating "' . $moduleName . '":"' . $migrationFile . '"...');
            if (MigrationManager::migrate($moduleName, $migrationFile)) {
                $this->logInfo('Migration for "' . $moduleName . '":"' . $migrationFile . '" complete, status updated.');
            } else {
                $this->logError('Migration failed. Aborting...');
            }
        }

        $this->logInfo('Finished.');
    }

    public function list(): void
    {
        $this->logInfo('Listing all migration files in all modules...');
        $migrationFiles = MigrationManager::getMigrationFiles();

        foreach ($migrationFiles as $moduleName => $files) {
            $this->outLine("================================");
            $this->outLine("Module: " . $moduleName);
            $this->outLine("\tFiles:");
            if (count($files) == 0) {
                $this->outLine('No migration files found.');
            }
            foreach ($files as $file) {
                $this->outLine("\t\t" . basename($file));
            }
        }
    }
}
