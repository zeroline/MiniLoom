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

use zeroline\MiniLoom\Modules\GlobalConfiguration\Model\SectionModel;

class SectorModel extends TimestampModel
{
    /**
     *
     * @var string
     */
    protected static string $tableName = "sector";

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
        'identifier' => array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::STR_MAX => array(255),
        ),
    );

    /**
     *
     * @return int
     */
    public function getSectorSchemaId(): int
    {
        return $this->sectorschemaid;
    }

    /**
     *
     * @param int $sectorId
     * @return void
     */
    public function setSectorSchemaId(int $sectorId): void
    {
        $this->sectorschemaid = $sectorId;
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
     * @return null|string
     */
    public function getSchema(): ?string
    {
        return $this->validationschema;
    }

    /**
     *
     * @param null|string $schema
     * @return void
     */
    public function setSchema(?string $schema): void
    {
        $this->validationschema = $schema;
    }

    /**
     *
     * @return array<mixed>
     */
    public function getSchemaArray(): array
    {
        return json_decode($this->getSchema() ?? '[]', true);
    }

    /**
     *
     * @return array<SectionModel>
     * @throws RuntimeException
     * @throws PDOException
     */
    public function getSections(): array
    {
        return SectionModel::repository()->where('sectorid', $this->getId())->read();
    }
}
