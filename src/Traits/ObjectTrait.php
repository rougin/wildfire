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

        $properties = [];

        if ($model instanceof CodeigniterModel) {
            $properties = $model->getProperties();
            $properties = $model->getRelationshipProperties($properties);
        }

        $tableInfo = $this->getTableInformation($tableName);

        foreach ($tableInfo as $column) {
            $key = $column->getField();

            $inColumns = ! empty($properties['columns']) && ! in_array($key, $properties['columns']);
            $inHiddenColumns = ! empty($properties['hidden']) && in_array($key, $properties['hidden']);

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
     * Gets the model class of the said table.
     *
     * @param  \Rougin\Wildfire\CodeigniterModel|string $table
     * @param  boolean                                  $isForeignKey
     * @return array
     */
    protected function getModel($table, $isForeignKey = false)
    {
        $newModel = $table;

        if (! is_object($table)) {
            $modelName = TableHelper::getModelName($table, $this->table, $isForeignKey);
            $newModel  = new $modelName;
        }

        $newTable = TableHelper::getNameFromModel($newModel);

        return [ strtolower($newTable), $newModel ];
    }

    /**
     * Returns the database information of the specified table.
     *
     * @param  string $tableName
     * @return array
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
            $newColumn   = TableHelper::getModelName($foreignTable, $this->table, true);

            $model->$newColumn = $foreignData;
        }
    }
}
