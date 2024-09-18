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

        /** @var \Rougin\Wildfire\Wildfire */
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
        return $this->init()->find($this->table(), $id);
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
        return $this->init()->get($this->table(), $limit, $offset);
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
        $this->init($wildfire);

        return $this;
    }

    /**
     * Initializes the Wildfire instance.
     *
     * @param \Rougin\Wildfire\Wildfire|null $wildfire
     *
     * @return \Rougin\Wildfire\Wildfire
     */
    private function init(Wildfire $wildfire = null)
    {
        if ($wildfire)
        {
            $this->wildfire = $wildfire;
        }
        else
        {
            $this->wildfire = new Wildfire($this->db);
        }

        return $this->wildfire;
    }
}
