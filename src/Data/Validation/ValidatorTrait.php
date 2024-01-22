<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 */

namespace zeroline\MiniLoom\Data\Validation;

use DeepCopy\Filter\Filter;
use zeroline\MiniLoom\Data\Validation\Validator as Validator;
use zeroline\MiniLoom\Data\Validation\ValidatorRule as ValidatorRule;
use zeroline\MiniLoom\Data\Validation\FilterMode as FilterMode;

trait ValidatorTrait
{
    /**
     * @var array
     */
    protected array $fieldsForValidation = array();

    /**
     * @var array
     */
    protected array $fieldsForValidationScopes = array();

    /**
     * @var array
     */
    protected array $fieldValidationErrors = array();

    /**
     * Sets the validation fields
     *
     * @param array $fieldsForValidation
     * @return void
     */
    public function setFieldsForValidation(array $fieldsForValidation): void
    {
        $this->fieldsForValidation = $fieldsForValidation;
    }

    /**
     *
     * @return array
     */
    public function getErrors() : array
    {
        return $this->fieldValidationErrors;
    }

    /**
     *
     * @param string $scopeName
     * @return boolean
     */
    public function hasScope(string $scopeName): bool
    {
        return (bool) array_key_exists($scopeName, $this->fieldsForValidationScopes);
    }

    /**
     *
     * @param string $scope
     * @return boolean
     * @throws \RuntimeException
     */
    public function isValid(?string $scope = null) : bool
    {
        $this->fieldValidationErrors = array();
        $fields = (is_null($scope) ? $this->fieldsForValidation : array_merge($this->fieldsForValidation, $this->fieldsForValidationScopes[$scope]));
        if (sizeof($fields) === 0) {
            return true;
        }

        $valid = true;
        foreach ($fields as $name => $rules) {
            $value = (isset($this->{$name}) ? $this->{$name} : null);
            foreach ($rules as $rule => $arguments) {
                $result = null;
                if (is_string($rule)) {
                    if (!isset($value) && $rule != ValidatorRule::REQUIRED) {
                        continue;
                    }

                    if ($rule == ValidatorRule::CUSTOM && is_callable($arguments)) {
                        $f = $arguments;
                        $result = $f($value, $this);
                    } elseif (method_exists(Validator::class, $rule)) {
                        $arguments = array_merge(array($value), $arguments);
                        $result = forward_static_call_array(array(Validator::class,$rule), $arguments);
                    } elseif (method_exists($this, $rule)) {
                        $arguments = array_merge(array($value), $arguments);
                        $result = call_user_func_array(array($this,$rule), $arguments);
                    } else {
                        throw new \RuntimeException('Validation rule method "' . $rule . '" cannot be found.');
                    }
                }

                if ($result !== true) {
                    $valid = false;
                    $this->fieldValidationErrors[] = array('field' => $name, 'rule' => $rule, 'ruleResult' => $result);
                }
            }
        }
        return $valid;
    }
}
