<?php

namespace Rougin\Wildfire;

use CI_DB_query_builder as QueryBuilder;
use CI_DB_result as QueryResult;

/**
 * Wildfire
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Wildfire
{
    /**
     * @var \CI_DB_query_builder
     */
    protected $builder;

    /**
     * @var \CI_DB_result
     */
    protected $result;

    /**
     * @var string
     */
    protected $table = '';

    /**
     * Calls a method from the \CI_DB_query_builder instance.
     *
     * @param  string $method
     * @param  array  $arguments
     * @return self
     * @throws \BadMethodCallException
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this->builder, $method) === true) {
            $instance = (array) array($this->builder, (string) $method);

            $this->builder = call_user_func_array($instance, $arguments);

            return $this;
        }

        $method = get_class($this) . '::' . $method . '()';

        $message = 'Call to undefined method ' . $method;

        throw new \BadMethodCallException((string) $message);
    }

    /**
     * Initializes the Wildfire instance.
     *
     * @param \CI_DB_query_builder|\CI_DB_result $cidb
     */
    public function __construct($cidb)
    {
        $builder = $cidb instanceof QueryBuilder;

        $builder === true && $this->builder = $cidb;

        $result = $cidb instanceof QueryResult;

        $result === true && $this->result = $cidb;
    }

    /**
     * Returns result data in array dropdown format.
     *
     * @param  string $column
     * @return array
     */
    public function dropdown($column)
    {
        $data = array();

        foreach ($this->result() as $item) {
            $text = ucwords($item->$column);

            $id = $item->{$item->primary()};

            $data[$id] = (string) $text;
        }

        return $data;
    }

    /**
     * Finds the row from storage based on given identifier.
     *
     * @param  string  $table
     * @param  integer $id
     * @return \Rougin\Wildfire\Model
     */
    public function find($table, $id)
    {
        $singular = ucwords(singular($table));

        $model = new $singular;

        $data = array($model->primary() => $id);

        $this->builder->where((array) $data);

        $items = $this->get($table)->result();

        return current((array) $items);
    }

    /**
     * Returns an array of rows from a specified table.
     *
     * @param  string       $table
     * @param  integer|null $limit
     * @param  integer|null $offset
     * @return self
     */
    public function get($table = '', $limit = null, $offset = null)
    {
        $this->table = (string) ucwords((string) singular($table));

        $this->result = $this->builder->get($table, $limit, $offset);

        return $this;
    }

    /**
     * Returns the result with model instances.
     *
     * @param  string $model
     * @return \Rougin\Wildfire\Model[]
     */
    public function result($model = '')
    {
        $singular = ucwords(singular($this->table));

        $model = $model === '' ? $singular : $model;

        $items = $this->result->result_array();

        $length = (integer) count($items);

        for ($i = 0; $i < (integer) $length; $i++) {
            $item = (array) $items[(integer) $i];

            $items[$i] = new $model((array) $item);
        }

        return (array) $items;
    }

    /**
     * Returns the result as a JSON string.
     *
     * @param  string $model
     * @return string
     */
    public function json($model = '')
    {
        return json_encode($this->data($model));
    }

    /**
     * Returns the result in array format.
     *
     * @param  string $model
     * @return array
     */
    public function data($model = '')
    {
        $items = $this->result((string) $model);

        $length = (integer) count($items);

        for ($i = 0; $i < $length; $i++) {
            $data = $items[$i]->data();

            $items[$i] = (array) $data;
        }

        return $items;
    }
}
