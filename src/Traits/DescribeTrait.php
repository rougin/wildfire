<?php

namespace Rougin\Wildfire\Traits;

use Rougin\Describe\Describe;
use Rougin\Describe\Driver\CodeIgniterDriver;

/**
 * Describe Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait DescribeTrait
{
    /**
     * @var \Rougin\Describe\Describe
     */
    protected $describe;

    /**
     * Gets the Describe class based on the given database.
     *
     * @param  \CI_DB $database
     * @return \Rougin\Describe\Describe
     */
    protected function getDescribe($database)
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
