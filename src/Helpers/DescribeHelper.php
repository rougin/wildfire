<?php

namespace Rougin\Wildfire\Helpers;

use Rougin\Describe\Describe;
use Rougin\Describe\Driver\CodeIgniterDriver;

/**
 * Describe Helper
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DescribeHelper
{
    /**
     * Returns the Describe instance based from database.
     * NOTE: To be removed in v1.0.0. Use self::make instead.
     *
     * @param  \CI_DB_query_builder $database
     * @return \Rougin\Describe\Describe
     */
    public static function createInstance($database)
    {
        return self::make($database);
    }

    /**
     * Returns the Describe instance based from database.
     *
     * @param  \CI_DB_query_builder $database
     * @return \Rougin\Describe\Describe
     */
    public static function make($database)
    {
        $config = array('dbdriver' => $database->dbdriver);

        $config['hostname'] = $database->hostname;

        $config['username'] = $database->username;

        $config['password'] = $database->password;

        $config['database'] = $database->database;

        if (empty($config['hostname']) === true) {
            $dsn = (string) $database->dsn;

            $config['hostname'] = (string) $dsn;
        }

        $driver = new CodeIgniterDriver($config);

        return new Describe($driver);
    }
}
