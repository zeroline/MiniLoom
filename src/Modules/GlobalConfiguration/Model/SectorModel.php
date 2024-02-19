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

use zeroline\MiniLoom\Modules\GlobalConfiguration\Model\SectionModel;

class SectorModel extends TimestampModel
{
    protected static string $tableName = "sector";

    protected static string $idColumn = "id";

    public function __construct(array|object $data = array())
    {
        parent::__construct($data);
    }

    protected array $ignoreFieldsOnSerialization = array(

    );

    protected array $fieldsForValidation = array(
        'identifier' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::STR_MAX => array(255),
        ),
    );

    protected array $fieldsForValidationScopes = array();

    public function getSectorSchemaId(): int
    {
        return $this->sectorschemaid;
    }

    public function setSectorSchemaId(int $sectorId): void
    {
        $this->sectorschemaid = $sectorId;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getSchema(): ?string
    {
        return $this->validationschema;
    }

    public function setSchema(?string $schema): void
    {
        $this->validationschema = $schema;
    }

    public function getSchemaArray(): array
    {
        return json_decode($this->getSchema(), true);
    }

    public function getSections(): array
    {
        return SectionModel::repository()->where('sectorid', $this->getId())->read();
    }
}
