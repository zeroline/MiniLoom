<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 */

namespace zeroline\MiniLoom\Data\Database\SQL;

use zeroline\MiniLoom\Data\Model as Model;
use zeroline\MiniLoom\Data\Validation\ValidatorTrait as ValidatorTrait;
use zeroline\MiniLoom\Data\Filter\FilterTrait as FilterTrait;

class ValidationModel extends Model
{
    use ValidatorTrait;
    use FilterTrait;

    const VALIDATION_SCOPE_CREATE = 'create';
    const VALIDATION_SCOPE_UPDATE = 'update';

    /**
     *
     * @param array<string, mixed>|object $data
     */
    public function __construct(array|object $data = array())
    {
        parent::__construct($data);
    }
}
