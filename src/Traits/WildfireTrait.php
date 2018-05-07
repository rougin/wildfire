<?php

namespace Rougin\Wildfire\Traits;

use Rougin\Wildfire\Wildfire;

/**
 * Wildfire Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait WildfireTrait
{
    /**
     * @var \Rougin\Wildfire\Wildfire
     */
    protected $wildfire;

    /**
     * Calls a method from the Wildfire instance.
     *
     * @param  string $method
     * @param  array  $arguments
     * @return self
     * @throws \BadMethodCallException
     */
    public function __call($method, $arguments)
    {
        $instance = (array) array($this->wildfire, (string) $method);

        $this->wildfire = call_user_func_array($instance, $arguments);

        return $this;
    }

    /**
     * Finds the row from storage based on given identifier.
     *
     * @param  string  $table
     * @param  integer $id
     * @return \Rougin\Wildfire\Model
     */
    public function find($id)
    {
        return $this->wildfire->find($this->table(), $id);
    }

    /**
     * Returns an array of rows from a specified table.
     *
     * @param  string       $table
     * @param  integer|null $limit
     * @param  integer|null $offset
     * @return self
     */
    public function get($limit = null, $offset = null)
    {
        return $this->wildfire->get($this->table(), $limit, $offset);
    }

    /**
     * Sets the Wildfire instance.
     *
     * @param  \Rougin\Wildfire\Wildfire $wildfire
     * @return self
     */
    public function wildfire(Wildfire $wildfire)
    {
        $this->wildfire = $wildfire;

        return $this;
    }
}
