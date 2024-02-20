<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\GlobalConfiguration
 *
 */

namespace zeroline\MiniLoom\Modules\GlobalConfiguration\Model;

use ReflectionException;
use RuntimeException;
use zeroline\MiniLoom\Modules\DataIntegrity\Model\TimestampModel;
use zeroline\MiniLoom\Data\Validation\ValidatorRule;

use zeroline\MiniLoom\Modules\GlobalConfiguration\Model\SectionModel;

class SectionFieldModel extends TimestampModel
{
    /**
     *
     * @var string
     */
    protected static string $tableName = "sectionfield";

    /**
     *
     * @var string
     */
    protected static string $idColumn = "id";

    /**
     *
     * @param array<string, mixed>|object $data
     * @return void
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function __construct(array|object $data = array())
    {
        parent::__construct($data);
    }

    /**
     * @var array<string, array<string, array<mixed>>>
     */
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

    /**
     * @var array<string, mixed>
     */
    protected array $fieldsForValidationScopes = array();

    /**
     *
     * @return int
     */
    public function getSectionId(): int
    {
        return $this->sectionid;
    }

    /**
     *
     * @param int $sectionid
     * @return void
     */
    public function setSectionId(int $sectionid): void
    {
        $this->sectionid = $sectionid;
    }

    /**
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     *
     * @param string $identifier
     * @return void
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     *
     * @return null|SectionModel
     */
    public function getSection(): ?SectionModel
    {
        $sectionModel = SectionModel::findOneById($this->getSectionId());
        if ($sectionModel instanceof SectionModel) {
            return $sectionModel;
        }
        return null;
    }

    /**
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     *
     * @param null|string $content
     * @return void
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     *
     * @return string
     */
    public function getTypeInformation(): string
    {
        return $this->typeinformation;
    }

    /**
     *
     * @param null|string $typeInformation
     * @return void
     */
    public function setTypeInformation(?string $typeInformation): void
    {
        $this->typeinformation = $typeInformation;
    }
}
