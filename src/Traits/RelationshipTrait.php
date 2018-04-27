<?php

namespace Rougin\Wildfire\Traits;

use Rougin\SparkPlug\Instance;

use Rougin\Wildfire\Helpers\TableHelper;

/**
 * Relationship Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 *
 * @property \CI_Loader $load
 */
trait RelationshipTrait
{
    /**
     * Defines an inverse one-to-one or many relationship.
     *
     * @var array
     */
    protected $belongs_to = [];

    /**
     * @var array
     */
    private $_with = [];

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
        $belongs = array();

        foreach ((array) $this->belongs_to as $item) {
            if (! in_array($item, $this->_with)) {
                continue;
            }

            isset($this->$item) || $this->load->model($item);

            $table = TableHelper::name(new $item);

            array_push($belongs, (string) $table);
        }

        $properties['belongs_to'] = $belongs;

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
