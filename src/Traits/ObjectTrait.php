<?php

namespace Rougin\Wildfire\Traits;

use CI_Model;
use Rougin\Describe\Column;

/**
 * Object Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 *
 * @property \Rougin\Describe\Describe $describe
 */
trait ObjectTrait
{
    /**
     * @var array
     */
    protected $tables = [];

    /**
     * Creates an object from the specified table and row.
     *
     * @param  string  $table
     * @param  object  $row
     * @param  boolean $isForeignKey
     * @return array
     */
    protected function createObject($table, $row, $isForeignKey = false)
    {
        list($model, $newTable) = $this->getModel($table, $isForeignKey);

        if (! array_key_exists($newTable, $this->tables)) {
            $tableInfo = $this->describe->getTable($newTable);

            $this->tables[$newTable] = $tableInfo;
        } else {
            $tableInfo = $this->tables[$newTable];
        }

        $columns = property_exists($model, 'columns') ? $model->columns : [];

        foreach ($tableInfo as $column) {
            $key = $column->getField();

            if (! empty($columns) && ! in_array($key, $model->columns)) {
                continue;
            }

            $model->$key = $row->$key;

            $this->setForeignField($model, $column);
        }

        return $model;
    }

    /**
     * Finds the row from the specified ID or with the list of delimiters from
     * the specified table.
     *
     * @param  string         $table
     * @param  array|integer  $delimiters
     * @param  boolean        $isForeignKey
     * @return object|boolean
     */
    abstract protected function find($table, $delimiters = [], $isForeignKey = false);

    /**
     * Sets the foreign field of the column, if any.
     *
     * @param  \CI_Model               $model
     * @param  \Rougin\Describe\Column $column
     * @return void
     */
    protected function setForeignField(CI_Model $model, Column $column)
    {
        if (! $column->isForeignKey()) {
            return;
        }

        $key = $column->getField();
        $foreignColumn = $column->getReferencedField();
        $foreignTable = $column->getReferencedTable();

        $delimiters = [ $foreignColumn => $model->$key ];
        $foreignData = $this->find($foreignTable, $delimiters, true);
        $newColumn = $this->getTableName($foreignTable, true);

        $model->$newColumn = $foreignData;
    }

    /**
     * Gets the model class of the said table.
     *
     * @param  string|null $table
     * @param  boolean     $isForeignKey
     * @return array
     */
    protected function getModel($table = null, $isForeignKey = false)
    {
        if ($table == null && $this->table == null) {
            return [ null, '' ];
        }

        $newTable = $this->getTableName($table, $isForeignKey);
        $model = new $newTable;

        if (property_exists($model, 'table')) {
            $newTable = $model->table;
        }

        return [ $model, strtolower($newTable) ];
    }

    /**
     * Parses the table name from Describe class.
     *
     * @param  string  $table
     * @param  boolean $isForeignKey
     * @return string
     */
    abstract protected function getTableName($table, $isForeignKey = false);
}
