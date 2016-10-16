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
     *
     * @param  string $table
     * @return string
     */
    public static function getModelName($table)
    {
        $tableName = $table;

        $tableName  = ucfirst(singular($tableName));
        $tableNames = explode('.', $tableName);

        return isset($tableNames[1]) ? $tableNames[1] : $tableName;
    }

    /**
     * Gets the table name specified from the model.
     * NOTE: To be removed in v1.0.0. Use $model->getTableName()
     *
     * @param  object $model
     * @return string
     */
    public static function getNameFromModel($model)
    {
        $tableName = '';

        if (method_exists($model, 'getTableName')) {
            $tableName = $model->getTableName();
        }

        if (isset($model->table)) {
            $tableName = $model->table;
        }

        return $tableName;
    }
}
