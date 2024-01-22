<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 * 
 * The ValidatedModel class extends the Model class and adds validation functionality.
 */

namespace zeroline\MiniLoom\Data\Validation;

use zeroline\MiniLoom\Data\Model as Model;
use zeroline\MiniLoom\Data\Validation\ValidatorTrait as ValidatorTrait;
use zeroline\MiniLoom\Data\Filter\FilterTrait as FilterTrait;

class ValidatedModel extends Model
{
    use ValidatorTrait;
    use FilterTrait;

    const VALIDATION_SCOPE_CREATE = 'create';
    const VALIDATION_SCOPE_UPDATE = 'update';

    public function __construct($data = null)
    {
        parent::__construct($data);
    }

    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        $this->filter();
        return $data;
    }
}
