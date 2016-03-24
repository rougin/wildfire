<?php

namespace Rougin\Wildfire;

use Rougin\Describe\Describe;
use Rougin\SparkPlug\Instance;
use Rougin\Describe\Driver\CodeIgniterDriver;

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
    use ResultTrait;

    /**
     * @var CI_DB
     */
    protected $db;

    /**
     * @var \Rougin\Describe\Describe
     */
    protected $describe;

    /**
     * @var CI_DB
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
        $config = [];

        if (empty($database)) {
            $ci = Instance::create();

            $ci->load->database();

            $database = $ci->db;
        }

        $this->db = $database;
        $this->query = $query;

        $config['default'] = [
            'dbdriver' => $database->dbdriver,
            'hostname' => $database->hostname,
            'username' => $database->username,
            'password' => $database->password,
            'database' => $database->database
        ];

        if (empty($config['default']['hostname'])) {
            $config['default']['hostname'] = $database->dsn;
        }

        $driver = new CodeIgniterDriver($config);
        $this->describe = new Describe($driver);
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
     * Return all rows from the specified table.
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
     * Sets the database class.
     * 
     * @param  CI_DB $database
     * @return self
     */
    public function setDatabase($database)
    {
        $this->db = $database;

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
        $method = Inflector::camelize($method);
        $result = $this;

        if (method_exists($this, $method)) {
            $class = [$this, $method];
            
            $result = call_user_func_array($class, $parameters);
        }

        return $result;
    }
}
