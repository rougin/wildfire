<?php

namespace Rougin\Wildfire\Helpers;

/**
 * Model Helper
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ModelHelper
{
    /**
     * Returns the model class of the said table.
     * NOTE: To be removed in v1.0.0. Use self::make instead.
     *
     * @param  string|object $table
     * @return array
     */
    public static function createInstance($table)
    {
        return self::make($table);
    }

    /**
     * Returns the model class of the said table.
     *
     * @param  string|object $table
     * @return array
     */
    public static function make($table)
    {
        if (is_string($table) === true) {
            $model = TableHelper::model($table);

            $model = new $model;

            $name = TableHelper::name($model);

            return array((string) $name, $model);
        }

        $name = TableHelper::name($table);

        return array((string) $name, $table);
    }
}
