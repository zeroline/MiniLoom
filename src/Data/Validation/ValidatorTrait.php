<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 */

namespace zeroline\MiniLoom\Data\Validation;

use zeroline\MiniLoom\Data\Validation\Validator as Validator;
use zeroline\MiniLoom\Data\Validation\ValidatorRule as ValidatorRule;
use RuntimeException;

trait ValidatorTrait
{
    /**
     * @var array<string, array<string, array<mixed>>>
     */
    protected array $fieldsForValidation = array();

    /**
     * @var array<string, mixed>
     */
    protected array $fieldsForValidationScopes = array();

    /**
     * @var array<array<mixed>>
     */
    protected array $fieldValidationErrors = array();

    /**
     * Sets the validation fields
     *
     * @param array<string, array<string, array<mixed>>> $fieldsForValidation
     * @return void
     */
    public function setFieldsForValidation(array $fieldsForValidation): void
    {
        $this->fieldsForValidation = $fieldsForValidation;
    }

    /**
     *
     * @return array<array<mixed>>
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
     * @throws RuntimeException
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
                    if (!isset($value) && $rule != ValidatorRule::REQUIRED->name) {
                        continue;
                    }

                    if ($rule == ValidatorRule::CUSTOM->name && is_callable($arguments)) {
                        $f = $arguments;
                        $result = $f($value, $this);
                    } elseif (method_exists(Validator::class, $rule)) {
                        $arguments = array_merge(array($value), $arguments);
                        $callable = array(Validator::class, $rule);
                        if(is_callable($callable)) {
                            $result = forward_static_call_array($callable, $arguments);
                        } else {
                            throw new RuntimeException('Validation rule method "' . $rule . '" cannot be found.');
                        }
                    } elseif (method_exists($this, $rule)) {
                        $arguments = array_merge(array($value), $arguments);
                        $callable = array($this, $rule);
                        if(is_callable($callable)) {
                            $result = call_user_func_array($callable, $arguments);
                        } else {
                            throw new RuntimeException('Validation rule method "' . $rule . '" cannot be found.');
                        }
                    } else {
                        throw new RuntimeException('Validation rule method "' . $rule . '" cannot be found.');
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
