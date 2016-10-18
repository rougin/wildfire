<?php

namespace Rougin\Wildfire;

use Rougin\Wildfire\Helpers\ModelHelper;
use Rougin\Wildfire\Helpers\DescribeHelper;

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
    use Traits\DatabaseTrait, Traits\ObjectTrait, Traits\ResultTrait;

    /**
     * @var \Rougin\Describe\Describe
     */
    protected $describe;

    /**
     * @param \CI_DB|null        $database
     * @param \CI_DB_result|null $query
     */
    public function __construct($database = null, $query = null)
    {
        $this->setDatabase($database);

        $this->describe = DescribeHelper::createInstance($this->db);
        $this->query    = $query;
    }

    /**
     * Finds the row from the specified ID or with the list of delimiters from
     * the specified table.
     *
     * @param  string         $tableName
     * @param  array|integer  $delimiters
     * @return object|boolean
     */
    public function find($tableName, $delimiters = [])
    {
        if (is_integer($delimiters)) {
            $primaryKey = $this->describe->getPrimaryKey($tableName);

            $delimiters = [ $primaryKey => $delimiters ];
        }

        $this->db->where($delimiters);

        $query = $this->db->get($tableName);

        if ($query->num_rows() > 0 && ! empty($query->row())) {
            return $this->createObject($tableName, $query->row());
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
        if (empty($table)) {
            $query = $this->db->last_query();

            preg_match('/\bfrom\b\s*(\w+)/i', $query, $matches);

            $this->table = $matches[1];

            return $this;
        }

        list($tableName, $model) = ModelHelper::createInstance($table);

        $this->table = $tableName;

        if ($this->query == null) {
            $this->query = $this->db->get($tableName);
        }

        if ($model == $table) {
            $this->table = $model;
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
