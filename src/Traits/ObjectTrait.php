<?php

namespace Rougin\Wildfire\Traits;

/**
 * Object Trait
 * 
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait ObjectTrait
{
    /**
     * Creates an object from the specified table and row.
     *
     * @param  string $table
     * @param  object $row
     * @return array
     */
    protected function createObject($table, $row)
    {
        $newTable = ucfirst(singular($table));
        $model = new $newTable;

        if ( ! array_key_exists($newTable, $this->tables)) {
            $tableInfo = $this->describe->getTable($newTable);

            $this->tables[$newTable] = $tableInfo;
        } else {
            $tableInfo = $this->tables[$newTable];
        }

        foreach ($row as $key => $value) {
            foreach ($tableInfo as $column) {
                if ($column->getField() != $key) {
                    continue;
                }

                $model->$key = $value;
            }
        }

        foreach ($row as $key => $value) {
            foreach ($tableInfo as $column) {
                if ($column->getField() != $key || ! $column->isForeignKey()) {
                    continue;
                }

                $foreignColumn = $column->getReferencedField();
                $foreignTable = $column->getReferencedTable();

                $delimiters = [ $foreignColumn => $value ];
                $foreignData = $this->find($foreignTable, $delimiters);

                $newColumn = singular($foreignTable);

                $model->$newColumn = $foreignData;
            }
        }

        return $model;
    }
}
