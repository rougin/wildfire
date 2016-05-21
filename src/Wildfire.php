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
    /**
     * @var CI_DB
     */
    protected $db;

    /**
     * @var \Rougin\Describe\Describe
     */
    protected $describe;

    /**
     * @var CI_DB_result
     */
    protected $query;

    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var array
     */
    protected $tables = [];

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

        foreach ($data as $row)
        {
            $object = $this->createObject($this->table, $row);

            array_push($result, $object);
        }

        return $result;
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
     * Sets the query result.
     * 
     * @param  CI_DB_result $query
     * @return self
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Creates an object from the specified table and row.
     *
     * @param  string $table
     * @param  object $row
     * @return array
     */
    protected function createObject($table, $row)
    {
        $newTable = ucfirst(Inflector::singular($table));
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
