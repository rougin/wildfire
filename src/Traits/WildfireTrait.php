<?php

namespace Rougin\Wildfire\Traits;

use Rougin\Wildfire\Wildfire;

/**
 * @property \CI_DB_result $db
 *
 * @method string table()
 *
 * @package Wildfire
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
trait WildfireTrait
{
    /**
     * @var \Rougin\Wildfire\Wildfire|null
     */
    protected $self = null;

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
        $class = array($this->self, $method);

        /** @var \Rougin\Wildfire\Wildfire */
        $self = call_user_func_array($class, $params);

        $this->self = $self;

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
        return $this->wildfire()->find($this->table(), $id);
    }

    /**
     * Returns an array of rows from a specified table.
     *
     * @param integer|null $limit
     * @param integer|null $offset
     *
     * @return \Rougin\Wildfire\Wildfire
     */
    public function get($limit = null, $offset = null)
    {
        return $this->wildfire()->get($this->table(), $limit, $offset);
    }

    /**
     * Sets the Wildfire instance.
     *
     * @param \Rougin\Wildfire\Wildfire|null $wildfire
     *
     * @return \Rougin\Wildfire\Wildfire
     */
    public function wildfire(Wildfire $wildfire = null)
    {
        if ($wildfire)
        {
            $this->self = $wildfire;
        }

        if (! $this->self)
        {
            $this->self = new Wildfire($this->db);
        }

        return $this->self;
    }
}
