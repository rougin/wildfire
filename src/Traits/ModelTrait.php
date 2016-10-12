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
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '';

    /**
     * Returns the specified columns of the model.
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Returns the specified hidden columns of the model.
     *
     * @return array
     */
    public function getHiddenColumns()
    {
        return $this->hidden;
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
