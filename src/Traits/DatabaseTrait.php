<?php

namespace Rougin\Wildfire\Traits;

use Rougin\Describe\Describe;
use Rougin\Describe\Driver\CodeIgniterDriver;
use Rougin\Wildfire\Helpers\DescribeHelper;

/**
 * Database Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait DatabaseTrait
{
    /**
     * @var \CI_DB_query_builder|null
     */
    protected $db;

    /**
     * @var \Rougin\Describe\Describe
     */
    protected $describe;

    /**
     * @var \CI_DB_result
     */
    protected $query;

    /**
     * Sets the database class.
     * NOTE: To be removed in v1.0.0. Use $this->database instead.
     *
     * @param  \CI_DB_query_builder $database
     * @return self
     */
    public function setDatabase($database)
    {
        return $this->database($database);
    }

    /**
     * Sets the database instance.
     *
     * @param  \CI_DB_query_builder $database
     * @return self
     */
    public function database($database)
    {
        $this->db = $database;

        $config = array('dbdriver' => $database->dbdriver);

        $config['hostname'] = $database->hostname;
        $config['username'] = $database->username;
        $config['password'] = $database->password;
        $config['database'] = $database->database;

        if (empty($config['hostname']) === true) {
            $config['hostname'] = $database->dsn;
        }

        $driver = new CodeIgniterDriver($config);

        $this->describe = new Describe($driver);

        return $this;
    }

    /**
     * Sets the query result.
     * NOTE: To be removed in v1.0.0. Use $this->query instead.
     *
     * @param  \CI_DB_result $query
     * @return self
     */
    public function setQuery($query)
    {
        return $this->query($query);
    }

    /**
     * Sets the query result instance.
     *
     * @param  \CI_DB_result $query
     * @return self
     */
    public function query($query)
    {
        $this->query = $query;

        return $this;
    }
}
