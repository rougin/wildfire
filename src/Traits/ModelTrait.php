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
    protected $columns = array();

    /**
     * Columns that will be hidden in the display.
     * If not set, it will hide a "password" column if it exists.
     *
     * @var array
     */
    protected $hidden = array('password');

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
     * Returns the specified primary key of the model.
     * NOTE: To be removed in v1.0.0. Use $this->primary instead.
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primary();
    }

    /**
     * Returns the specified primary key of the model.
     *
     * @return string
     */
    public function primary()
    {
        return $this->primary_key;
    }

    /**
     * Returns the values from the model's properties.
     * NOTE: To be removed in v1.0.0. Use $this->properties instead.
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties();
    }

    /**
     * Returns the values from the model's properties.
     *
     * @return array
     */
    public function properties()
    {
        $properties = array();

        $properties['columns'] = $this->columns;

        $properties['hidden']  = $this->hidden;

        return $properties;
    }

    /**
     * Returns the specified table name of the model.
     * NOTE: To be removed in v1.0.0. Use $this->table instead.
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->table();
    }

    /**
     * Returns the specified table name of the model.
     *
     * @return string
     */
    public function table()
    {
        $table = plural(strtolower(get_class($this)));

        return $this->table ? $this->table : $table;
    }
}
