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

class SectionFieldModel extends TimestampModel
{
    protected static string $tableName = "sectionfield";

    protected static string $idColumn = "id";

    public function __construct(array|object $data = array())
    {
        parent::__construct($data);
    }

    protected array $ignoreFieldsOnSerialization = array(

    );

    protected array $fieldsForValidation = array(
        'sectionid' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::IS_NUMBER => array(),
        ),
        'identifier' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::STR_MAX => array(255),
        ),
    );

    protected array $fieldsForValidationScopes = array();

    public function getSectionId(): int
    {
        return $this->sectionid;
    }

    public function setSectionId(int $sectionid): void
    {
        $this->sectionid = $sectionid;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getSection(): ?SectionModel
    {
        return SectionModel::findOneById($this->getSectionId());
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getTypeInformation(): string
    {
        return $this->typeinformation;
    }

    public function setTypeInformation(?string $typeInformation): void
    {
        $this->typeinformation = $typeInformation;
    }
}
