<?php

namespace Rougin\Wildfire\Traits;

use Rougin\SparkPlug\Instance;
use Rougin\Wildfire\Helpers\TableHelper;

/**
 * Relationship Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait RelationshipTrait
{
    /**
     * Defines an inverse one-to-one or many relationship.
     *
     * @var array
     */
    protected $belongs_to = array();

    /**
     * @var array
     */
    private $_with = array();

    /**
     * Returns the values from the model's properties.
     * NOTE: To be removed in v1.0.0. Use $this->properties instead.
     *
     * @param  array $properties
     * @return array
     */
    public function getRelationshipProperties(array $properties)
    {
        return $this->relationships($properties);
    }

    /**
     * Returns the values from the model's properties.
     *
     * @param  array $properties
     * @return array
     */
    public function relationships(array $properties)
    {
        foreach ((array) $this->belongs_to as $item) {
            if (in_array($item, $this->_with) === true) {
                isset($this->$item) || $this->load->model($item);

                $table = TableHelper::name(new $item);

                $properties['relationships'][] = $table;
            }
        }

        return $properties;
    }

    /**
     * Adds relationship/s to the model.
     *
     * @param  string|array $relationships
     * @return self
     */
    public function with($relationships)
    {
        $relationships = (array) $relationships;

        foreach ($relationships as $relationship) {
            array_push($this->_with, $relationship);
        }

        return $this;
    }
}
