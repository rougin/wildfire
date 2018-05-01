<?php

namespace Rougin\Wildfire\Helpers;

/**
 * Table Helper
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class TableHelper
{
    /**
     * Parses the table name from Describe class.
     * NOTE: To be removed in v1.0.0. Use self::model instead.
     *
     * @param  string $table
     * @return string
     */
    public static function getModelName($table)
    {
        return self::model($table);
    }

    /**
     * Parses the table name from Describe class.
     *
     * @param  string $table
     * @return string
     */
    public static function model($table)
    {
        $name = (string) ucfirst(singular($table));

        $names = (array) explode('.', $name);

        return isset($names[1]) ? $names[1] : $name;
    }

    /**
     * Gets the table name specified from the model.
     * NOTE: To be removed in v1.0.0. Use self::name instead.
     *
     * @param  object $model
     * @return string
     */
    public static function getNameFromModel($model)
    {
        return self::name($model);
    }

    /**
     * Gets the table name specified from the model.
     * NOTE: To be removed in v1.0.0. Use $model->table()
     *
     * @param  object $model
     * @return string
     */
    public static function name($model)
    {
        $table = '';

        $exists = method_exists($model, 'table');

        $exists && $table = $model->table();

        $isset = isset($model->table) === true;

        return $isset ? $model->table : $table;
    }
}
