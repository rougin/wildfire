<?php

namespace Rougin\Wildfire\Traits;

use Rougin\Describe\Column;

use Rougin\Wildfire\CodeigniterModel;

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
     * @var array
     */
    protected $tables = [];

    /**
     * Creates an object from the specified table and row.
     *
     * @param  string|\Rougin\Wildfire\CodeigniterModel $table
     * @param  object                                   $row
     * @param  boolean                                  $isForeignKey
     * @return array
     */
    protected function createObject($table, $row, $isForeignKey = false)
    {
        list($tableName, $model) = $this->getModel($table, $isForeignKey);

        if (! $model instanceof \CI_Model) {
            return $model;
        }

        $properties = $this->getModelProperties($model);
        $properties = $this->getRelationshipProperties($model, $properties);
        $tableInfo  = $this->getTableInformation($tableName);

        foreach ($tableInfo as $column) {
            $key = $column->getField();

            $inHiddenColumns = ! empty($properties['hidden']) && in_array($key, $properties['hidden']);
            $inColumns       = ! empty($properties['columns']) && ! in_array($key, $properties['columns']);

            if ($inColumns || $inHiddenColumns) {
                continue;
            }

            $model->$key = $row->$key;

            $this->setForeignField($model, $column, $properties);
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
     * Parses the table name from Describe class.
     *
     * @param  string  $table
     * @param  boolean $isForeignKey
     * @return string
     */
    abstract protected function getClassTableName($table, $isForeignKey = false);

    /**
     * Gets the model class of the said table.
     *
     * @param  \Rougin\Wildfire\CodeigniterModel|string|null $table
     * @param  boolean                                       $isForeignKey
     * @return array
     */
    protected function getModel($table = null, $isForeignKey = false)
    {
        if (empty($table) && empty($this->table)) {
            return [ '', null ];
        }

        $newModel = $table;
        $newTable = '';

        if (! is_object($table)) {
            $newTable = $this->getClassTableName($table, $isForeignKey);
            $newModel = new $newTable;
        }

        $newTable = $this->getModelTableName($newModel);

        return [ strtolower($newTable), $newModel ];
    }

    /**
     * Returns the values from the model's properties.
     *
     * @param  \CI_Model|\Rougin\Wildfire\CodeigniterModel $model
     * @return array
     */
    protected function getModelProperties($model)
    {
        $properties = [ 'column' => [], 'hidden' => [] ];

        if (method_exists($model, 'getColumns')) {
            $properties['columns'] = $model->getColumns();
        } elseif (property_exists($model, 'columns')) {
            // NOTE: To be removed in v1.0.0
            $properties['columns'] = $model->columns;
        }

        // NOTE: To be removed in v1.0.0 (if condition only)
        if (method_exists($model, 'getHiddenColumns')) {
            $properties['hidden'] = $model->getHiddenColumns();
        }

        return $properties;
    }

    /**
     * Gets the table name specified in the model.
     * NOTE: To be removed in v1.0.0
     *
     * @param  \CI_Model $model
     * @return string
     */
    protected function getModelTableName($model)
    {
        if (method_exists($model, 'getTableName')) {
            return $model->getTableName();
        }

        if (property_exists($model, 'table')) {
            return $model->table;
        }

        return '';
    }

    /**
     * Returns the values from the model's properties.
     *
     * @param  \CI_Model|\Rougin\Wildfire\CodeigniterModel $model
     * @param  array                                       $properties
     * @return array
     */
    public function getRelationshipProperties($model, array $properties)
    {
        if (method_exists($model, 'getBelongsToRelationships')) {
            $properties['belongs_to'] = $model->getBelongsToRelationships();
        }

        if (method_exists($model, 'getRelationships')) {
            $properties['with'] = $model->getRelationships();
        }

        $belongsTo = [];

        if (isset($properties['with']) && isset($properties['belongs_to'])) {
            foreach ($properties['belongs_to'] as $item) {
                if (! in_array($item, $properties['with'])) {
                    continue;
                }

                $ci = \Rougin\SparkPlug\Instance::create();

                $ci->load->model($item);

                $model = new $item;

                array_push($belongsTo, $this->getModelTableName($model));
            }
        }

        $properties['belongs_to'] = $belongsTo;

        return $properties;
    }

    /**
     * Returns the database information of the specified table.
     *
     * @param  string $tableName
     * @return string
     */
    public function getTableInformation($tableName)
    {
        if (! array_key_exists($tableName, $this->tables)) {
            $tableInfo = $this->describe->getTable($tableName);

            $this->tables[$tableName] = $tableInfo;

            return $tableInfo;
        }

        return $this->tables[$tableName];
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
        if (! $column->isForeignKey()) {
            return;
        }

        $columnName    = $column->getField();
        $foreignColumn = $column->getReferencedField();
        $foreignTable  = $column->getReferencedTable();

        if (in_array($foreignTable, $properties['belongs_to'])) {
            $delimiters  = [ $foreignColumn => $model->$columnName ];
            $foreignData = $this->find($foreignTable, $delimiters, true);
            $newColumn   = $this->getClassTableName($foreignTable, true);

            $model->$newColumn = $foreignData;
        }
    }
}
