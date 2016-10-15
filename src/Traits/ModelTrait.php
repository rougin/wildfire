<?php

namespace Rougin\Wildfire\Traits;

/**
 * Model Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait ModelTrait
{
    /**
     * Columns that will be displayed.
     * If not set, it will get the columns from the database table.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * Columns that will be hidden in the display.
     * If not set, it will hide a "password" column if it exists.
     *
     * @var array
     */
    protected $hidden = [ 'password' ];

    /**
     * Model's default primary key or unique identifier.
     *
     * @var string
     */
    protected $primary_key = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '';

    /**
     * Gets the specified primary key of the model.
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    /**
     * Returns the values from the model's properties.
     *
     * @return array
     */
    public function getProperties()
    {
        $properties = [];

        $properties['columns'] = $this->columns;
        $properties['hidden']  = $this->hidden;

        return $properties;
    }

    /**
     * Gets the specified table name of the model.
     *
     * @return string
     */
    public function getTableName()
    {
        if (! $this->table) {
            return plural(strtolower(get_class($this)));
        }

        return $this->table;
    }
}
