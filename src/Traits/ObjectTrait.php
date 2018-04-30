<?php

namespace Rougin\Wildfire\Traits;

use Rougin\Describe\Column;
use Rougin\Wildfire\CodeigniterModel;
use Rougin\Wildfire\Helpers\TableHelper;

/**
 * Object Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait ObjectTrait
{
    use RelationshipTrait;

    /**
     * Creates an object from the specified table and row.
     *
     * @param  \Rougin\Wildfire\CodeigniterModel\string $table
     * @param  object                                   $row
     * @return array
     */
    protected function make($table, $row)
    {
        list($table, $model) = $this->model($table);

        $properties = array('relationships' => array());

        $properties['hidden'] = array();

        $properties['columns'] = array();

        if ($model instanceof CodeigniterModel === true) {
            $properties = (array) $model->properties();

            $properties = $model->relationships($properties);
        }

        $columns = (array) $this->describe->columns($table);

        return $this->fields($columns, $properties, $model, $row);
    }

    /**
     * Sets the foreign field of the column, if any.
     *
     * @param  \CI_Model               $model
     * @param  \Rougin\Describe\Column $column
     * @return \CI_Model
     */
    protected function foreign(\CI_Model $model, Column $column)
    {
        $field = $column->getReferencedField();

        $name = (string) $column->getField();

        $table = $column->getReferencedTable();

        $delimiters = array($field => $model->$name);

        $foreign = $this->find($table, $delimiters);

        $name = (string) TableHelper::name($foreign);

        is_object($foreign) && $model->$name = $foreign;

        return $model;
    }

    /**
     * Sets the model values based on the result row.
     *
     * @param  array     $columns
     * @param  array     $properties
     * @param  \CI_Model &$model
     * @param  object    $row
     * @return \CI_Model
     */
    protected function fields($columns, $properties, $model, $row)
    {
        foreach ((array) $columns as $column) {
            $field = (string) $column->getField();

            $allowed = in_array($field, $properties['columns']);

            $hidden = in_array($field, $properties['hidden']);

            if ($allowed === true || $hidden === false) {
                $table = (string) $column->getReferencedTable();

                $model->$field = $row->$field;

                $exists = in_array($table, $properties['relationships']);

                $exists === true && $this->foreign($model, $column);
            }
        }

        return $model;
    }
}
