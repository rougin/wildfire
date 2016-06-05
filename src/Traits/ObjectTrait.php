<?php

namespace Rougin\Wildfire\Traits;

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
     * @param  string $table
     * @param  object $row
     * @return array
     */
    protected function createObject($table, $row)
    {
        list($model, $newTable) = $this->getModel($table);

        if ( ! array_key_exists($newTable, $this->tables)) {
            $tableInfo = $this->describe->getTable($newTable);

            $this->tables[$newTable] = $tableInfo;
        }

        if (isset($this->tables[$newTable])) {
            $tableInfo = $this->tables[$newTable];
        }

        foreach ($tableInfo as $column) {
            if ( ! property_exists($row, $column->getField())) {
                continue;
            }

            $key = $column->getField();

            $model->$key = $row->$key;

            if ($column->isForeignKey()) {
                $foreignColumn = $column->getReferencedField();
                $foreignTable = $column->getReferencedTable();

                $delimiters = [ $foreignColumn => $model->$key ];
                $foreignData = $this->find($foreignTable, $delimiters);
                $newColumn = $this->getTableName($foreignTable);

                $model->$newColumn = $foreignData;
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
     * @return object|boolean
     */
    abstract protected function find($table, $delimiters = []);

    /**
     * Gets the modal class of the said table.
     * 
     * @param  string|null $table
     * @return array
     */
    protected function getModel($table = null)
    {
        if ($table == null) {
            return [ null, '' ];
        }

        $newTable = $this->getTableName($table);
        $model = new $newTable;

        if (property_exists($model, 'table')) {
            $newTable = $model->table;
        }

        return [ $model, strtolower($newTable) ];
    }

    /**
     * Parses the table name from Describe class.
     * 
     * @param  string $table
     * @return string
     */
    abstract protected function getTableName($table);
}
