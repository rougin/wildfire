<?php

namespace Rougin\Wildfire;

use CI_DB_result as QueryResult;

/**
 * @method \Rougin\Wildfire\Wildfire where(mixed $key, mixed $value = null, boolean $escape = null)
 *
 * @package Wildfire
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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
     * @param string  $method
     * @param mixed[] $params
     *
     * @return self
     * @throws \BadMethodCallException
     */
    public function __call($method, $params)
    {
        if (! method_exists($this->builder, $method))
        {
            $method = get_class($this) . '::' . $method . '()';

            $text = 'Call to undefined method ' . $method;

            throw new \BadMethodCallException((string) $text);
        }

        /** @var callable */
        $class = array($this->builder, $method);

        /** @var \CI_DB_query_builder */
        $builder = call_user_func_array($class, $params);

        $this->builder = $builder;

        return $this;
    }

    /**
     * Initializes the Wildfire instance.
     *
     * @param \CI_DB_query_builder|\CI_DB_result $cidb
     */
    public function __construct($cidb)
    {
        if ($cidb instanceof QueryResult)
        {
            $this->result = $cidb;
        }
        else
        {
            $this->builder = $cidb;
        }
    }

    /**
     * Returns result data in array dropdown format.
     *
     * @param string $column
     *
     * @return string[]
     */
    public function dropdown($column)
    {
        $data = array();

        foreach ($this->result() as $item)
        {
            $result = $item->data();

            /** @var integer */
            $id = $result[$item->primary()];

            /** @var string */
            $text = $result[$column];

            $data[$id] = ucwords($text);
        }

        return $data;
    }

    /**
     * Finds the row from storage based on given identifier.
     *
     * @param string  $table
     * @param integer $id
     *
     * @return \Rougin\Wildfire\Model|null
     */
    public function find($table, $id)
    {
        $singular = ucwords(singular($table));

        /** @var \Rougin\Wildfire\Model */
        $model = new $singular;

        $data = array($model->primary() => $id);

        $this->builder->where($data);

        $items = $this->get($table)->result();

        return $items ? $items[0] : null;
    }

    /**
     * Returns an array of rows from a specified table.
     *
     * @param string       $table
     * @param integer|null $limit
     * @param integer|null $offset
     *
     * @return self
     */
    public function get($table = '', $limit = null, $offset = null)
    {
        $this->table = ucwords(singular($table));

        $this->result = $this->builder->get($table, $limit, $offset);

        return $this;
    }

    /**
     * Returns the result with model instances.
     *
     * @param string $model
     *
     * @return \Rougin\Wildfire\Model[]
     */
    public function result($model = '')
    {
        $singular = ucwords(singular($this->table));

        $model = $model === '' ? $singular : $model;

        $items = $this->result->result_array();

        $length = (int) count($items);

        for ($i = 0; $i < (int) $length; $i++)
        {
            $item = $items[$i];

            $items[$i] = new $model($item);
        }

        return $items;
    }

    /**
     * Returns the result as a JSON string.
     *
     * @param string $model
     *
     * @return string
     */
    public function json($model = '')
    {
        return (string) json_encode($this->data($model));
    }

    /**
     * Returns the result in array format.
     *
     * @param string $model
     *
     * @return array<string, mixed>[]
     */
    public function data($model = '')
    {
        $items = $this->result($model);

        $result = array();

        foreach ($items as $item)
        {
            $result[] = $item->data();
        }

        return $result;
    }
}
