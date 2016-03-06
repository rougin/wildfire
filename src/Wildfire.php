<?php

namespace Rougin\Wildfire;

use Rougin\Describe\Describe;
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
     * @var CI_DB
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
     * @param CI_DB      $database
     * @param CI_DB|null $query
     */
    public function __construct($database, $query = null)
    {
        $config = [];

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
    public function as_dropdown($description = 'description')
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
        $table = $this->stripTableSchema($table);
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

                $foreignTable = $this->stripTableSchema($foreignTable);
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
     * Strips the table schema from the table name.
     * 
     * @param  string $table
     * @return string
     */
    protected function stripTableSchema($table)
    {
        if (strpos($table, '.') !== false) {
            return substr($table, strpos($table, '.') + 1);
        }

        return $table;
    }
}
