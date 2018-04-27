<?php

namespace Rougin\Wildfire\Traits;

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

        $this->describe = DescribeHelper::make($database);

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
