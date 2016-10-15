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
     * Gets the Describe class based on the given database.
     *
     * @param  \CI_DB $database
     * @return \Rougin\Describe\Describe
     */
	public static function createInstance($database)
	{
		$config = [
            'default' => [
                'dbdriver' => $database->dbdriver,
                'hostname' => $database->hostname,
                'username' => $database->username,
                'password' => $database->password,
                'database' => $database->database
            ],
        ];

        if (empty($config['default']['hostname'])) {
            $config['default']['hostname'] = $database->dsn;
        }

        $driver = new CodeIgniterDriver($config);

        return new Describe($driver);
	}
}
