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
    protected $belongs_to = [];

    /**
     * @var array
     */
    private $_with = [];

    /**
     * Returns the values from the model's properties.
     *
     * @param  array $properties
     * @return array
     */
    public function getRelationshipProperties(array $properties)
    {
        $belongsTo = [];

        $ci = Instance::create();

        foreach ($this->belongs_to as $item) {
            if (! in_array($item, $this->_with)) {
                continue;
            } elseif (! isset($ci->$item)) {
                $ci->load->model($item);
            }

            array_push($belongsTo, TableHelper::getNameFromModel(new $item));
        }

        $properties['belongs_to'] = $belongsTo;

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
        if (is_string($relationships)) {
            $relationships = [ $relationships ];
        }

        foreach ($relationships as $relationship) {
            array_push($this->_with, $relationship);
        }

        return $this;
    }
}
