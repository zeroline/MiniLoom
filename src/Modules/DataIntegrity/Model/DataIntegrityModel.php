<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Modules\DataIntegrity
 *
 */

namespace zeroline\MiniLoom\Modules\DataIntegrity\Model;

use ReflectionException;
use RuntimeException;
use zeroline\MiniLoom\Modules\DataIntegrity\Model\TimestampModel;
use zeroline\MiniLoom\Modules\DataIntegrity\Lib\EntryState;
use zeroline\MiniLoom\Data\Validation\ValidatorRule;

class DataIntegrityModel extends TimestampModel
{
    /**
     * The active state
     * @var string
     */
    public const FIELD_ACTIVE_STATE = 'activeState';

    /**
    *
    * @param array<string, mixed>|object $data
    * @return void
    * @throws ReflectionException
    * @throws RuntimeException
    */
    public function __construct(array|object $data = array())
    {
        $this->fieldsForValidation[self::FIELD_ACTIVE_STATE] = array(
            ValidatorRule::REQUIRED => array(),
            ValidatorRule::IS_NUMBER => array(),
            ValidatorRule::IN_ARRAY => array(
                array(EntryState::ACTIVE, EntryState::DELETED, EntryState::INACITVE, EntryState::REQUEST_FOR_PERM_DELETION)
            ),
        );

        parent::__construct($data);
    }

    /**
     * Returns the active state
     *
     * @return integer
     */
    public function getActiveState(): int
    {
        return $this->activeState;
    }

    /**
     * Indicates if the entry is active
     *
     * @return boolean
     */
    public function getIsActive(): bool
    {
        return ($this->getActiveState() === EntryState::ACTIVE);
    }

    /**
     * Indicates if the entry is inactive
     *
     * @return boolean
     */
    public function getIsInActive(): bool
    {
        return ($this->getActiveState() === EntryState::INACITVE);
    }

    /**
     * Indicates if the entry is marked a deleted
     *
     * @return boolean
     */
    public function getIsDeleted(): bool
    {
        return ($this->getActiveState() === EntryState::DELETED);
    }

    /**
     * Indicates if the entry is marked for real deletion
     *
     * @return boolean
     */
    public function getIsMarkedForPermanentDeletion(): bool
    {
        return ($this->getActiveState() === EntryState::REQUEST_FOR_PERM_DELETION);
    }

    /**
     *
     * @return void
     */
    public function markAsActive(): void
    {
        $this->activeState = EntryState::ACTIVE;
    }

    /**
     *
     * @return void
     */
    public function markAsInActive(): void
    {
        $this->activeState = EntryState::INACITVE;
    }

    /**
     *
     * @return void
     */
    public function markAsDeleted(): void
    {
        $this->activeState = EntryState::DELETED;
    }

    /**
     *
     * @return void
     */
    public function markAsMarkedForPermanentDeletion(): void
    {
        $this->activeState = EntryState::REQUEST_FOR_PERM_DELETION;
    }
}
