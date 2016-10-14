<?php

namespace Rougin\Wildfire\Traits;

/**
 * Relationship Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 *
 * @property array $belongs_to
 */
trait RelationshipTrait
{
    /**
     * @var array
     */
    private $_with = [];

    /**
     * Returns "belongs to" relationships.
     *
     * @return
     */
    public function getBelongsToRelationships()
    {
        return $this->belongs_to;
    }

    /**
     * Gets the defined relationships.
     *
     * @return
     */
    public function getRelationships()
    {
        return $this->_with;
    }

    /**
     * Adds a relationship/s to the model.
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
