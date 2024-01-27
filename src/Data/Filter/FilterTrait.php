<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 * The Validator class provides a set of static methods to validate data.
 */

namespace zeroline\MiniLoom\Data\Filter;

use zeroline\MiniLoom\Data\Filter\Filter as Filter;
use zeroline\MiniLoom\Data\Filter\FilterMode as FilterMode;
use RuntimeException;

trait FilterTrait
{
    /**
     * @var array<string, array<string, mixed>>
     */
    protected array $fieldsForFiltering = array();

    /**
     * @var FilterMode
     */
    protected FilterMode $filterMode = FilterMode::FILTER_MODE_BOTH;

    /**
     * @param FilterMode $filterMode
     * @return void
     */
    protected function setFilterMode(FilterMode $filterMode): void
    {
        $this->filterMode = $filterMode;
    }

    /**
     * @return FilterMode
     */
    protected function getFilterMode(): FilterMode
    {
        return $this->filterMode;
    }

    /**
     * Perform filter actions for configured fields.
     * Filter action may change the fields value.
     * Prefered use before handing data to a client
     *
     * @return void
     */
    public function filter(): void
    {
        $fields = $this->fieldsForFiltering;
        foreach ($fields as $name => $rules) {
            $value = (isset($this->{$name}) ? $this->{$name} : null);
            foreach ($rules as $rule => $arguments) {
                if (method_exists(Filter::class, $rule)) {
                    $arguments = array_merge(array($value), $arguments);
                    $callable = array(Filter::class,$rule);
                    if (is_callable($callable)) {
                        $this->{$name} = forward_static_call_array($callable, $arguments);
                    } else {
                        throw new RuntimeException('Filter method not callable');
                    }
                }
            }
        }
    }
}
