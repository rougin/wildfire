<?php

namespace Rougin\Wildfire;

/**
 * Result Trait
 *
 * Contains functions for retrieving data.
 * 
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait ResultTrait
{
    /**
     * @var array
     */
    protected $tables = [];

    /**
     * Lists all data in dropdown format.
     *
     * @param  string $description
     * @return array
     */
    public function asDropdown($description = 'description')
    {
        $data = [];
        $id = $this->describe->getPrimaryKey($this->table);

        $result = $this->query->result();

        foreach ($result as $row) {
            $data[$row->$id] = ucwords($row->$description);
        }

        return $data;
    }

    /**
     * Return the result
     * 
     * @return object
     */
    public function result()
    {
        $data = $this->getQueryResult();
        $result = [];

        if (empty($this->table)) {
            $this->get();
        }

        foreach ($data as $row)
        {
            $object = $this->createObject($this->table, $row);

            array_push($result, $object);
        }

        return $result;
    }

    /**
     * Create an object from the specified data
     *
     * @param  string $table
     * @param  object $row
     * @return array
     */
    protected function createObject($table, $row)
    {
        $newTable = ucfirst(Inflector::singular($table));
        $model = new $newTable;

        if ( ! array_key_exists($table, $this->tables)) {
            $tableInfo = $this->describe->getTable($table);

            $this->tables[$table] = $tableInfo;
        } else {
            $tableInfo = $this->tables[$table];
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

                $newColumn = Inflector::singular($foreignTable);

                $model->$newColumn = $foreignData;
            }
        }

        return $model;
    }

    /**
     * Gets the data result from the specified query.
     * 
     * @return array|object
     */
    protected function getQueryResult()
    {
        $result = $this->query;

        if (method_exists($this->query, 'result')) {
            $result = $this->query->result();
        }

        return $result;
    }
}