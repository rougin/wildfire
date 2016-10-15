<?php

namespace Rougin\Wildfire\Helpers;

/**
 * Model Helper
 * NOTE: To be removed in v1.0.0
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ModelHelper
{
    /**
     * Gets the table name specified in the model.
     *
     * @param  object $model
     * @return string
     */
    public static function getTableName($model)
    {
        if (method_exists($model, 'getTableName')) {
            return $model->getTableName();
        }

        if (property_exists($model, 'table')) {
            return $model->table;
        }

        return '';
    }
}
