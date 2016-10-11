<?php

namespace Rougin\Wildfire\Traits;

/**
 * Result Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 *
 * @property \Rougin\Describe\Describe $describe
 * @property \CI_DB_null               $query
 */
trait ResultTrait
{
    /**
     * @var string
     */
    protected $table = '';

    /**
     * Lists all data in dropdown format.
     *
     * @param  string $description
     * @return array
     */
    public function asDropdown($description = 'description')
    {
        $data = [];

        $tableName = $this->table;

        if ($tableName instanceof \Rougin\Wildfire\CodeigniterModel) {
            $tableName = $this->table->getTableName();
        }

        $id = $this->describe->getPrimaryKey($tableName);

        $result = $this->query->result();

        foreach ($result as $row) {
            $data[$row->$id] = ucwords($row->$description);
        }

        return $data;
    }

    /**
     * Returns the total number of rows from the result.
     *
     * @return integer
     */
    public function count()
    {
        return $this->query->count_all_results();
    }

    /**
     * Creates an object from the specified table and row.
     *
     * @param  string  $table
     * @param  object  $row
     * @param  boolean $isForeignKey
     * @return array
     */
    abstract protected function createObject($table, $row, $isForeignKey = false);

    /**
     * Returns all rows from the specified table.
     *
     * @param  string $table
     * @return self
     */
    abstract public function get($table = '');

    /**
     * Returns the result.
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

        foreach ($data as $row) {
            $object = $this->createObject($this->table, $row);

            array_push($result, $object);
        }

        return $result;
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
