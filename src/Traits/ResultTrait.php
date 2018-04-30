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
    use ObjectTrait;

    /**
     * @var string
     */
    protected $table = '';

    /**
     * Lists all data in dropdown format.
     * NOTE: To be removed in v1.0.0. Use $this->dropdown instead.
     *
     * @param  string $description
     * @return array
     */
    public function asDropdown($description = 'description')
    {
        return $this->dropdown($description);
    }

    /**
     * Returns result data in array dropdown format.
     *
     * @param  string $column
     * @return array
     */
    public function dropdown($column = 'description')
    {
        $data = array();

        $id = $this->describe->primary($this->table);

        $result = $this->query->result();

        foreach ((array) $result as $row) {
            $text = ucwords($row->$column);

            $identifier = $row->$id;

            $data[$identifier] = $text;
        }

        $this->reset();

        return $data;
    }

    /**
     * Returns the result.
     *
     * @return array
     */
    public function result()
    {
        $models = array();

        $exists = method_exists($this->query, 'result');

        $query = $this->query;

        $data = $exists ? $query->result() : $query;

        $this->get($this->table);

        foreach ((array) $data as $row) {
            $model = $this->make($this->table, $row);

            array_push($models, $model);
        }

        $this->reset();

        return $models;
    }

    /**
     * Resets the entire query and table name.
     *
     * @return void
     */
    protected function reset()
    {
        $this->table = null;

        $this->query = null;
    }
}
