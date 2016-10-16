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
     * Gets the model class of the said table.
     *
     * @param  string|object $tableName
     * @return array
     */
    public static function createInstance($tableName)
    {
        if (is_object($tableName)) {
            return [ TableHelper::getNameFromModel($tableName), $tableName ];
        }

        $modelName = TableHelper::getModelName($tableName);
        $newModel  = new $modelName;

        return [ TableHelper::getNameFromModel($newModel), $newModel ];
    }
}
