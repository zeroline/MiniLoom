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
use zeroline\MiniLoom\Data\Database\SQL\DatabaseAbstractionModel;

class TimestampModel extends DatabaseAbstractionModel {
    /**
     * The date time format
     * @var string
     */
    public const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * The created field
     * @var string
     */
    public const FIELD_CREATED = 'created';

    /**
     * The updated field
     * @var string
     */
    public const FIELD_UPDATED = 'updated';

    /**
    *
    * @param array<string, mixed>|object $data
    * @return void
    * @throws ReflectionException
    * @throws RuntimeException
    */
    public function __construct(array|object $data = array())
    {
        $this->addAutomaticField(self::FIELD_UPDATED, function ($model) {
            $model->updated = date(self::DATE_TIME_FORMAT);
        });
        $this->addAutomaticField(self::FIELD_CREATED, function ($model) {
            if ($model->isNew()) {
                $model->created = date(self::DATE_TIME_FORMAT);
            }
        });

        parent::__construct($data);
    }
}