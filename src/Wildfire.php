<?php

namespace Rougin\Wildfire;

use Rougin\Wildfire\Helpers\TableHelper;

/**
 * Wildfire
 *
 * Yet another wrapper for CodeIgniter's Query Builder Class.
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Wildfire extends \CI_Model
{
    use Traits\DatabaseTrait, Traits\ResultTrait;

    /**
     * Initializes the Wildfire instance.
     *
     * @param \CI_DB_query_builder|null $database
     * @param \CI_DB_result|null        $query
     */
    public function __construct($database = null, $query = null)
    {
        empty($database) || $this->database($database);

        $this->query = $query;
    }

    /**
     * Finds the row from the specified ID or with the
     * list of delimiters from the specified table.
     *
     * @param  string         $table
     * @param  array|integer  $delimiters
     * @return object|boolean
     */
    public function find($table, $delimiters = [])
    {
        if (is_integer($delimiters) === true) {
            $primary = $this->describe->primary($table);

            $delimiters = array($primary => $delimiters);
        }

        $this->db->where($delimiters);

        $query = $this->db->get($table);

        if ($query->num_rows() > 0 && $query->row()) {
            return $this->make($table, $query->row());
        }

        return false;
    }

    /**
     * Returns all rows from the specified table.
     *
     * @param  mixed $table
     * @return self
     */
    public function get($table = '')
    {
        // Guess the specified table from the query
        if (empty($table) === true) {
            $pattern = '/\bfrom\b\s*(\w+)/i';

            $query = $this->db->last_query();

            preg_match($pattern, $query, $matches);

            $this->table = (string) $matches[1];

            return $this;
        }

        return $this->prepare($table);
    }

    /**
     * Returns the model class of the said table.
     *
     * @param  string|object $table
     * @return array
     */
    protected function model($table)
    {
        if (is_string($table) === true) {
            $model = TableHelper::model($table);

            $model = new $model;

            $name = TableHelper::name($model);

            return array((string) $name, $model);
        }

        $name = TableHelper::name($table);

        return array((string) $name, $table);
    }

    /**
     * Prepares the query and table properties.
     *
     * @param  string|object $table
     * @return self
     */
    protected function prepare($table)
    {
        list($name, $model) = (array) $this->model($table);

        $this->query || $this->query = $this->db->get($name);

        $this->table = $model === $table ? $model : $name;

        return $this;
    }

    /**
     * Calls methods from this class in underscore case.
     * NOTE: To be removed in v1.0.0. Methods are now only one word.
     *
     * @param  string $method
     * @param  mixed  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $method = (string) camelize($method);

        $result = $this;

        if (method_exists($this, $method) === true) {
            $instance = array($this, (string) $method);

            $result = call_user_func_array($instance, $parameters);
        }

        return $result;
    }
}
