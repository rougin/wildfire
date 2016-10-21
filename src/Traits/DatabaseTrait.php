<?php

namespace Rougin\Wildfire\Traits;

use Rougin\SparkPlug\Instance;

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
     *
     * @param  \CI_DB_query_builder $database
     * @return self
     */
    public function setDatabase($database)
    {
        $this->db = $database;

        $this->describe = DescribeHelper::createInstance($database);

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
