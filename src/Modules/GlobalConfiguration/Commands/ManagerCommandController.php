<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\GlobalConfiguration
 *
 */

namespace zeroline\MiniLoom\Modules\GlobalConfiguration\Commands;

use zeroline\MiniLoom\Controlling\CLI\Controller;

use zeroline\MiniLoom\Modules\GlobalConfiguration\Model\SectorModel;
use zeroline\MiniLoom\Modules\GlobalConfiguration\Service\ConfigurationService;

class ManagerCommandController extends Controller
{
    public function overview(): void
    {
        $sectors = SectorModel::repository()->read();
        foreach ($sectors as $sector) {
            $this->outLine('================================');
            $this->outLine($sector->getIdentifier());
            foreach ($sector->getSections() as $section) {
                $this->outLine("\t" . $section->getIdentifier());
                foreach ($section->getFields() as $field) {
                    $this->outLine("\t\t" . $field->getIdentifier() . "\t" . $field->getContent());
                }
            }
        }
    }

    public function update(string $sectorIdentifier, string $sectionIdentifier, string $fieldIdentifier, string $value): void
    {
        if (ConfigurationService::setConfig($sectorIdentifier, $sectionIdentifier, $fieldIdentifier, $value)) {
            $this->logInfo('Field updated');
        } else {
            $this->logError('Update failed');
        }
    }

    public function deleteSector(string $sectorIdentifier): void
    {
        if (ConfigurationService::deleteSectorByIdentifier($sectorIdentifier)) {
            $this->logInfo('Sector removed');
        } else {
            $this->logError('Sector could not be removed');
        }
    }

    public function deleteSection(string $sectorIdentifier, string $sectionIdentifier): void
    {
        if (ConfigurationService::deleteSectionByIdentifier($sectorIdentifier, $sectionIdentifier)) {
            $this->logInfo('Section removed');
        } else {
            $this->logError('Section could not be removed');
        }
    }

    public function deleteField(string $sectorIdentifier, string $sectionIdentifier, string $fieldIdentifier): void
    {
        if (ConfigurationService::deleteFieldByIdentifier($sectorIdentifier, $sectionIdentifier, $fieldIdentifier)) {
            $this->logInfo('Field removed');
        } else {
            $this->logError('Field could not be removed');
        }
    }
}
