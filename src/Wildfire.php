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

        if ($cidb instanceof QueryResult) {
            $this->result = $cidb;

            $this->table = $this->guess();
        }
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
     * @return \Rougin\Wildfire\Model[]
     */
    public function result()
    {
        $items = $this->result->result_array();

        $length = (integer) count($items);

        for ($i = 0; $i < (integer) $length; $i++) {
            $item = (array) $items[(integer) $i];

            $items[$i] = new $this->table($item);
        }

        return (array) $items;
    }

    /**
     * Returns the guessed table name from PDOStatement.
     *
     * @return string
     */
    protected function guess()
    {
        $statement = $this->result->result_id;

        $pattern = '/\bfrom\b\s*(\w+)/i';

        $query = $statement->queryString;

        preg_match($pattern, $query, $matches);

        return ucwords(singular($matches[1]));
    }
}
