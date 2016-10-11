<?php

namespace Rougin\Wildfire;

use Rougin\Wildfire\Traits\ObjectTrait;
use Rougin\Wildfire\Traits\ResultTrait;
use Rougin\Wildfire\Traits\DatabaseTrait;
use Rougin\Wildfire\Traits\DescribeTrait;

/**
 * Wildfire
 *
 * Yet another wrapper for CodeIgniter's Query Builder Class.
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Wildfire
{
    use DatabaseTrait, DescribeTrait, ObjectTrait, ResultTrait;

    /**
     * @param \CI_DB|null        $database
     * @param \CI_DB_result|null $query
     */
    public function __construct($database = null, $query = null)
    {
        $this->setDatabase($database);

        $this->describe = $this->getDescribe($this->db);
        $this->query = $query;
    }

    /**
     * Deletes the data from the specified table by the given delimiters.
     *
     * @param  string|object $table
     * @param  integer|array $delimiters
     * @return mixed
     */
    public function delete($table, $delimiters)
    {
        $tableName = ($table instanceof CodeigniterModel) ? $table->getTableName() : $table;

        if (is_integer($delimiters)) {
            $delimiters = [ $this->describe->getPrimaryKey($tableName) => $delimiters ];
        }

        $this->db->where($delimiters);

        return $this->db->delete($tableName);
    }

    /**
     * Finds the row from the specified ID or with the list of delimiters from
     * the specified table.
     *
     * @param  object|string  $table
     * @param  array|integer  $delimiters
     * @param  boolean        $isForeignKey
     * @return object|boolean
     */
    public function find($table, $delimiters = [], $isForeignKey = false)
    {
        list($model, $tableName) = $this->getModel($table, $isForeignKey);

        if (! is_array($delimiters)) {
            $primaryKey = $this->describe->getPrimaryKey($tableName);

            $delimiters = [ $primaryKey => $delimiters ];
        }

        $this->db->where($delimiters);

        $query = $this->db->get($tableName);

        if ($query->num_rows() > 0) {
            if ($table == $model) {
                $tableName = $model;
            }

            return $this->createObject($tableName, $query->row(), $isForeignKey);
        }

        return false;
    }

    /**
     * Returns all rows from the specified table.
     *
     * @param  object|string $table
     * @return self
     */
    public function get($table = '')
    {
        list($model, $tableName) = $this->getModel($table);

        if ($this->query == null) {
            $this->query = $this->db->get($tableName);
        }

        $this->table = $tableName;

        if ($table == $model) {
            $this->table = $model;
        }

        // Guess the specified table from the query
        if (empty($table)) {
            $query = $this->db->last_query();

            preg_match('/\bfrom\b\s*(\w+)/i', $query, $matches);

            $this->table = $matches[1];
        }

        return $this;
    }

    /**
     * Calls methods from this class in underscore case.
     *
     * @param  string $method
     * @param  mixed  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $method = camelize($method);
        $result = $this;

        if (method_exists($this, $method)) {
            $class = [$this, $method];
            
            $result = call_user_func_array($class, $parameters);
        }

        return $result;
    }
}
