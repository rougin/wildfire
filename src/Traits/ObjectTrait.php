<?php

namespace Rougin\Wildfire\Traits;

use Rougin\Describe\Column;

use Rougin\Wildfire\CodeigniterModel;
use Rougin\Wildfire\Helpers\ModelHelper;
use Rougin\Wildfire\Helpers\TableHelper;

/**
 * Object Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 *
 * @property \Rougin\Describe\Describe $describe
 * @property string                    $table
 */
trait ObjectTrait
{
    /**
     * Creates an object from the specified table and row.
     * NOTE: To be removed in v1.0.0. Use $this->make instead.
     *
     * @param  string|\Rougin\Wildfire\CodeigniterModel $table
     * @param  object                                   $row
     * @return array
     */
    protected function createObject($table, $row)
    {
        return $this->make($table, $row);
    }

    /**
     * Creates an object from the specified table and row.
     *
     * @param  \Rougin\Wildfire\CodeigniterModel\string $table
     * @param  object                                   $row
     * @return array
     */
    protected function make($table, $row)
    {
        list($table, $model) = ModelHelper::make($table);

        $properties = array('belongs_to' => array());

        $properties['hidden'] = array();

        $properties['columns'] = array();

        if ($model instanceof CodeigniterModel === true) {
            $properties = $model->properties();

            $properties = $model->relationships($properties);
        }

        $columns = $this->describe->columns($table);

        $this->setModelFields($model, $row, $properties, $columns);

        return $model;
    }

    /**
     * Finds the row from the specified ID or with the list of delimiters from
     * the specified table.
     *
     * @param  string         $table
     * @param  array|integer  $delimiters
     * @return object|boolean
     */
    abstract protected function find($table, $delimiters = []);

    /**
     * Checks if the field is not a column or intended to be hidden.
     *
     * @param  string  $key
     * @param  array   $properties
     * @return boolean
     */
    protected function notColumnOrIsHidden($key, array $properties)
    {
        $isColumn = in_array($key, $properties['columns']);
        $isHidden = in_array($key, $properties['hidden']);

        return (! empty($properties['columns']) && ! $isColumn) || $isHidden;
    }

    /**
     * Sets the foreign field of the column, if any.
     *
     * @param  \CI_Model               $model
     * @param  \Rougin\Describe\Column $column
     * @param  array                   $properties
     * @return void
     */
    protected function setForeignField(\CI_Model $model, Column $column, array $properties)
    {
        $columnName = $column->getField();

        $foreignColumn = $column->getReferencedField();
        $foreignTable  = $column->getReferencedTable();

        if (in_array($foreignTable, $properties['belongs_to'])) {
            $delimiters = [ $foreignColumn => $model->$columnName ];
            $foreign    = $this->find($foreignTable, $delimiters);

            if (is_object($foreign)) {
                $tableName  = TableHelper::getNameFromModel($foreign);

                $model->$tableName = $foreign;
            }
        }
    }

    /**
     * Sets the model values based on the result row.
     *
     * @param  \CI_Model &$model
     * @param  object    $row
     * @param  array     $properties
     * @param  array     $tableInformation
     * @return void
     */
    protected function setModelFields(&$model, $row, array $properties, $tableInformation)
    {
        foreach ($tableInformation as $column) {
            $key = $column->getField();

            if ($this->notColumnOrIsHidden($key, $properties)) {
                continue;
            }

            $model->$key = $row->$key;

            $this->setForeignField($model, $column, $properties);
        }
    }
}
