<?php

namespace Rougin\Wildfire\Traits;

use Rougin\SparkPlug\Instance;

/**
 * Database Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 *
 * @property string $table
 */
trait DatabaseTrait
{
    /**
     * @var \CI_DB
     */
    protected $db;

    /**
     * @var \CI_DB_result
     */
    protected $query;

    /**
     * Parses the table name from Describe class.
     *
     * @param  string  $table
     * @param  boolean $isForeignKey
     * @return string
     */
    protected function getClassTableName($table, $isForeignKey = false)
    {
        $tableName = $table;

        if (! $isForeignKey && $this->table) {
            $tableName = $this->table;

            if (is_object($this->table)) {
                $tableName = $this->table->getTableName();
            }
        }

        $tableName  = ucfirst(singular($tableName));
        $tableNames = explode('.', $tableName);

        return isset($tableNames[1]) ? $tableNames[1] : $tableName;
    }

    /**
     * Sets the database class.
     *
     * @param  \CI_DB|null $database
     * @return self
     */
    public function setDatabase($database = null)
    {
        $ci = Instance::create();

        $ci->load->helper('inflector');

        if (empty($database)) {
            $ci->load->database();

            $this->db = $ci->db;

            return $this;
        }

        $this->db = $database;

        return $this;
    }

    /**
     * Sets the query result.
     *
     * @param  \CI_DB_result $query
     * @return self
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }
}
