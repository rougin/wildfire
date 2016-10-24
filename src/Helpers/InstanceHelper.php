<?php

namespace Rougin\Wildfire\Helpers;

use Rougin\Wildfire\Wildfire;

/**
 * Instance Helper
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class InstanceHelper
{
    /**
     * @var \Rougin\Wildfire\Wildfire
     */
    protected static $wildfire;

    /**
     * Factory method to create Wildfire instance.
     *
     * @param  \CI_DB_query_builder|null $database
     * @param  \CI_DB_result|null        $query
     * @return void
     */
    public static function create($database = null, $query = null)
    {
        if (empty(self::$wildfire)) {
            self::$wildfire = new Wildfire($database, $query);
        }
    }

    /**
     * Returns the Wildfire instance.
     *
     * @return \Rougin\Wildfire\Wildfire
     */
    public static function get()
    {
        if (! empty(self::$wildfire)) {
            return self::$wildfire;
        }

        return null;
    }
}