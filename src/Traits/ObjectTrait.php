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
     *
     * @param  string|\Rougin\Wildfire\CodeigniterModel $table
     * @param  object                                   $row
     * @return array
     */
    protected function createObject($table, $row)
    {
        list($tableName, $model) = ModelHelper::createInstance($table);

        $properties = [
            'belongs_to' => [],
            'columns'    => [],
            'hidden'     => [],
        ];

        if ($model instanceof CodeigniterModel) {
            $properties = $model->getProperties();
            $properties = $model->getRelationshipProperties($properties);
        }

        $columns = $this->describe->getTable($tableName);

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
                $tableName = TableHelper::getNameFromModel($foreign);

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
     * @param  array     $columns
     * @return void
     */
    protected function setModelFields(&$model, $row, $properties, $columns)
    {
        foreach ($columns as $column) {
            $key = $column->getField();

            $inColumns = in_array($key, $properties['columns']);
            $isHidden  = in_array($key, $properties['hidden']);

            if (! $inColumns || $isHidden) {
                continue;
            }

            $model->$key = $row->$key;

            $this->setForeignField($model, $column, $properties);
        }
    }
}
