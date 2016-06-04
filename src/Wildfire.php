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
     * @var \CI_DB_result
     */
    protected $query;

    /**
     * @var string
     */
    protected $table = '';

    /**
     * @param CI_DB|null        $database
     * @param CI_DB_result|null $query
     */
    public function __construct($database = null, $query = null)
    {
        $this->setDatabase($database);

        $this->describe = $this->getDescribe($this->db);
        $this->query = $query;
    }

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
     * Finds the row from the specified ID or with the list of delimiters from
     * the specified table.
     *
     * @param  string         $table
     * @param  array|integer  $delimiters
     * @return object|boolean
     */
    public function find($table, $delimiters = [])
    {
        if ( ! is_array($delimiters)) {
            $primaryKey = $this->describe->getPrimaryKey($table);

            $delimiters = [ $primaryKey => $delimiters ];
        }

        $this->db->where($delimiters);

        $query = $this->db->get($table);

        if ($query->num_rows() > 0) {
            return $this->createObject($table, $query->row());
        }

        return false;
    }

    /**
     * Returns all rows from the specified table.
     * 
     * @param  string $table
     * @return self
     */
    public function get($table = '')
    {
        if ($this->query == null) {
            $this->query = $this->db->get($table);
        }

        $this->table = $table;

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
    public function __call($method, $parameters) {
        $method = camelize($method);
        $result = $this;

        if (method_exists($this, $method)) {
            $class = [$this, $method];
            
            $result = call_user_func_array($class, $parameters);
        }

        return $result;
    }
}
