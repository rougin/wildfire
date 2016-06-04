<?php

namespace Rougin\Wildfire\Traits;

/**
 * Result Trait
 * 
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 *
 * @property \CI_DB_null $query
 */
trait ResultTrait
{
    /**
     * @var string
     */
    protected $table = '';

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
        $query = $this->query;
        $result = [];

        if (method_exists($this->query, 'result')) {
            $query = $this->query->result();
        }

        if (empty($this->table)) {
            $this->get();
        }

        foreach ($query as $row) {
            array_push($result, $this->createObject($this->table, $row));
        }

        return $result;
    }
}
