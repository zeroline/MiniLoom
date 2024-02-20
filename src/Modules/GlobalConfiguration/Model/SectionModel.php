<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\GlobalConfiguration
 *
 */

namespace zeroline\MiniLoom\Modules\GlobalConfiguration\Model;

use RuntimeException;
use PDOException;
use ReflectionException;
use zeroline\MiniLoom\Modules\DataIntegrity\Model\TimestampModel;
use zeroline\MiniLoom\Data\Validation\ValidatorRule;

use zeroline\MiniLoom\Modules\GlobalConfiguration\Model\SectorModel;
use zeroline\MiniLoom\Modules\GlobalConfiguration\Model\SectionFieldModel;

class SectionModel extends TimestampModel
{
    /**
     *
     * @var string
     */
    protected static string $tableName = "section";

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
        'sectorid' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::IS_NUMBER => array(),
        ),
        'identifier' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::STR_MAX => array(255),
        ),
    );

    /**
     *
     * @return int
     */
    public function getSectorId(): int
    {
        return $this->sectorid;
    }

    /**
     *
     * @param int $sectorId
     * @return void
     */
    public function setSectorId(int $sectorId): void
    {
        $this->sectorid = $sectorId;
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
     * @return null|SectorModel
     */
    public function getSector(): ?SectorModel
    {
        $result = SectorModel::findOneById($this->getSectorId());
        if ($result instanceof SectorModel) {
            return $result;
        }
        return null;
    }

    /**
     *
     * @return array<SectionFieldModel>
     * @throws RuntimeException
     * @throws PDOException
     */
    public function getFields(): array
    {
        return SectionFieldModel::repository()->where('sectionid', $this->getId())->read();
    }
}
