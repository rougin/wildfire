<?php

namespace Rougin\Wildfire\Traits;

use Rougin\Wildfire\Wildfire;

/**
 * @method string table()
 *
 * @package Wildfire
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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
     * @param string  $method
     * @param mixed[] $params
     *
     * @return self
     * @throws \BadMethodCallException
     */
    public function __call($method, $params)
    {
        /** @var callable */
        $class = array($this->wildfire, $method);

        $wildfire = call_user_func_array($class, $params);

        $this->wildfire = $wildfire;

        return $this;
    }

    /**
     * Finds the row from storage based on given identifier.
     *
     * @param integer $id
     *
     * @return \Rougin\Wildfire\Model|null
     */
    public function find($id)
    {
        return $this->wildfire->find($this->table(), $id);
    }

    /**
     * Returns an array of rows from a specified table.
     *
     * @param integer|null $limit
     * @param integer|null $offset
     *
     * @return self
     */
    public function get($limit = null, $offset = null)
    {
        return $this->wildfire->get($this->table(), $limit, $offset);
    }

    /**
     * Sets the Wildfire instance.
     *
     * @param \Rougin\Wildfire\Wildfire $wildfire
     *
     * @return self
     */
    public function wildfire(Wildfire $wildfire)
    {
        $this->wildfire = $wildfire;

        return $this;
    }
}
