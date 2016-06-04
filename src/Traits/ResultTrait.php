<?php

namespace Rougin\Wildfire\Traits;

/**
 * Result Trait
 * 
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait ResultTrait
{
    /**
     * Returns the result.
     * 
     * @return object
     */
    public function result()
    {
        $data = $this->getQueryResult();
        $result = [];

        if (empty($this->table)) {
            $this->get();
        }

        foreach ($data as $row)
        {
            $object = $this->createObject($this->table, $row);

            array_push($result, $object);
        }

        return $result;
    }

    /**
     * Gets the data result from the specified query.
     * 
     * @return array|object
     */
    protected function getQueryResult()
    {
        $result = $this->query;

        if (method_exists($this->query, 'result')) {
            $result = $this->query->result();
        }

        return $result;
    }
}
