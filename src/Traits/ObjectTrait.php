<?php

namespace Rougin\Wildfire\Traits;

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

        $belongsTo     = [];
        $columns       = [];
        $hidden        = [];
        $relationships = [];

        // NOTE: To be removed in v1.0.0 (move to belongsTo)
        if (method_exists($model, 'getBelongsToRelationships')) {
            $belongsTo = $model->getBelongsToRelationships();
        }

        // NOTE: To be removed in v1.0.0 (move to columns)
        if (property_exists($model, 'columns')) {
            $columns = $model->columns;
        } elseif (property_exists($model, '_columns')) {
            $columns = $model->getColumns();
        }

        // NOTE: To be removed in v1.0.0 (move to hidden)
        if (method_exists($model, 'getHiddenColumns')) {
            $hidden = $model->getHiddenColumns();
        }

        // NOTE: To be removed in v1.0.0 (move to relationships)
        if (method_exists($model, 'getRelationships')) {
            $relationships = $model->getRelationships();
        }

        foreach ($tableInfo as $column) {
            $key = $column->getField();

            $hasInColumns       = ! empty($columns) && ! in_array($key, $columns);
            $hasInHidden        = ! empty($hidden) && ! in_array($key, $hidden);
            $isCodeigniterModel = $model instanceof \Rougin\Wildfire\CodeigniterModel;

            if ($hasInColumns || ! $hasInHidden) {
                continue;
            }

            $model->$key = $row->$key;

            // NOTE: To be removed in v1.0.0 (if condition only)
            if ($isCodeigniterModel) {
                foreach ($belongsTo as $key => $value) {
                    $option = $value;

                    if (is_string($value)) {
                        $option = [ 'primary_key' => $value . '_id', 'model' => $value ];
                    }

                    $tableName = (new $option['model'])->getTableName();

                    $isForeignPrimaryKey = $option['primary_key'] == $column->getField();
                    $isForeignTable      = $tableName == $column->getReferencedTable();

                    if (in_array($option['model'], $relationships) && $isForeignPrimaryKey && $isForeignTable) {
                        $this->setForeignField($model, $column);
                    }
                }
            }
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
     * Gets the model class of the said table.
     *
     * @param  object|string|null $table
     * @param  boolean            $isForeignKey
     * @return array
     */
    protected function getModel($table = null, $isForeignKey = false)
    {
        if ($table == null && $this->table == null) {
            return [ null, '' ];
        }

        if ($table instanceof \Rougin\Wildfire\CodeigniterModel) {
            return [ $table, strtolower($table->getTableName()) ];
        }

        $newTable = $this->getTableName($table, $isForeignKey);
        $newModel = new $newTable;

        // NOTE: To be removed in v1.0.0
        if (property_exists($newModel, 'table')) {
            $newTable = $newModel->table;
        } elseif (property_exists($newModel, '_table')) {
            $newTable = $newModel->getTableName();
        }

        return [ $newModel, strtolower($newTable) ];
    }

    /**
     * Parses the table name from Describe class.
     *
     * @param  string  $table
     * @param  boolean $isForeignKey
     * @return string
     */
    abstract protected function getTableName($table, $isForeignKey = false);

    /**
     * Sets the foreign field of the column, if any.
     *
     * @param  \CI_Model               $model
     * @param  \Rougin\Describe\Column $column
     * @return void
     */
    protected function setForeignField(\CI_Model $model, Column $column)
    {
        if (! $column->isForeignKey()) {
            return;
        }

        $columnKey     = $column->getField();
        $foreignColumn = $column->getReferencedField();
        $foreignTable  = $column->getReferencedTable();

        $delimiters  = [ $foreignColumn => $model->$columnKey ];
        $foreignData = $this->find($foreignTable, $delimiters, true);
        $newColumn   = $this->getTableName($foreignTable, true);

        $model->$newColumn = $foreignData;
    }
}
