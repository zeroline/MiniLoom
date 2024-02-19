<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\GlobalConfiguration
 *
 */

namespace zeroline\MiniLoom\Modules\GlobalConfiguration\Model;

use zeroline\MiniLoom\Modules\DataIntegrity\Model\TimestampModel;
use zeroline\MiniLoom\Data\Validation\ValidatorRule;

use zeroline\MiniLoom\Modules\GlobalConfiguration\Model\SectorModel;
use zeroline\MiniLoom\Modules\GlobalConfiguration\Model\SectionFieldModel;

class SectionModel extends TimestampModel
{
    protected static string $tableName = "section";

    protected static string $idColumn = "id";

    public function __construct(array|object $data = array())
    {
        parent::__construct($data);
    }

    protected array $ignoreFieldsOnSerialization = array(

    );

    protected array $fieldsForValidation = array(
        'sectorid' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::IS_NUMBER => array(),
        ),
        'identifier' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::STR_MAX => array(255),
        ),
    );

    protected array $fieldsForValidationScopes = array();

    public function getSectorId(): int
    {
        return $this->sectorid;
    }

    public function setSectorId(int $sectorId): void
    {
        $this->sectorid = $sectorId;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getSector(): ?SectorModel
    {
        return SectorModel::findOneById($this->getSectorId());
    }

    public function getFields(): array
    {
        return SectionFieldModel::repository()->where('sectionid', $this->getId())->read();
    }
}
