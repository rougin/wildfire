<?php

namespace Rougin\Wildfire\Traits;

/**
 * Result Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 *
 * @property \Rougin\Describe\Describe $describe
 * @property \CI_DB_result|null        $query
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

        $id = $this->describe->getPrimaryKey($this->table);

        $result = $this->query->result();

        foreach ($result as $row) {
            $data[$row->$id] = ucwords($row->$description);
        }

        $this->resetQuery();

        return $data;
    }

    /**
     * Creates an object from the specified table and row.
     *
     * @param  string $table
     * @param  object $row
     * @return array
     */
    abstract protected function createObject($table, $row);

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
        $result = [];

        if (empty($this->table)) {
            $this->get();
        }

        foreach ($this->getQueryResult() as $row) {
            $object = $this->createObject($this->table, $row);

            array_push($result, $object);
        }

        $this->resetQuery();

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

        if ($this->table) {
            $this->get($this->table);
        }

        return $result;
    }

    /**
     * Resets the entire query and table name.
     *
     * @return void
     */
    protected function resetQuery()
    {
        $this->table = null;
        $this->query = null;
    }
}
