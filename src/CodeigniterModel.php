<?php

namespace Rougin\Wildfire;

/**
 * Codeigniter Model
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class CodeigniterModel extends \CI_Model
{
    /**
     * Defines an inverse one-to-one or many relationship.
     *
     * @var array
     */
    protected $_belongs_to = [];

    /**
     * Columns that will be displayed.
     * If not set, it will get the columns from the database table.
     *
     * @var array
     */
    protected $_columns = [];

    /**
     * Columns that will be hidden in the display.
     * If not set, it will hide a "password" column if it exists.
     *
     * @var array
     */
    protected $_hidden = [ 'password' ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $_table = '';

    /**
     * @var \Rougin\Wildfire\Wildfire
     */
    private $_wildfire;

    /**
     * @var array
     */
    private $_with = [];

    public function __construct()
    {
        parent::__construct();

        $this->load->database();

        $this->_wildfire = new Wildfire($this->db);
    }

    /**
     * Returns all of the models from the database.
     *
     * @return array
     */
    public function all()
    {
        return $this->findBy([]);
    }

    /**
     * Deletes the specified ID of the model from the database.
     *
     * @param  integer $id
     * @return void
     */
    public function delete($id)
    {
        return $this->_wildfire->delete($this, $id);
    }

    /**
     * Finds the specified model from the database.
     *
     * @param  integer $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->_wildfire->find($this, $id);
    }

    /**
     * Finds the specified model from the database by the given delimiters.
     *
     * @param  array $delimiters
     * @return mixed
     */
    public function findBy(array $delimiters)
    {
        $this->db->where($delimiters);

        return $this->get()->result();
    }

    /**
     * Returns all rows from the specified table.
     *
     * @return self
     */
    public function get()
    {
        return $this->_wildfire->get($this);
    }

    /**
     * Returns "belongs to" relationships.
     *
     * @return
     */
    public function getBelongsToRelationships()
    {
        return $this->_belongs_to;
    }

    /**
     * Returns the specified columns of the model.
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->_columns;
    }

    /**
     * Returns the specified hidden columns of the model.
     *
     * @return array
     */
    public function getHiddenColumns()
    {
        return $this->_hidden;
    }

    /**
     * Gets the defined relationships.
     *
     * @return
     */
    public function getRelationships()
    {
        return $this->_with;
    }

    /**
     * Gets the specified table name of the model.
     *
     * @return string
     */
    public function getTableName()
    {
        if (! $this->_table) {
            return plural(strtolower(get_class($this)));
        }

        return $this->_table;
    }

    /**
     * Adds a relationship/s to the model.
     *
     * @param  string|array $relationships
     * @return self
     */
    public function with($relationships)
    {
        if (is_string($relationships)) {
            $relationships = [ $relationships ];
        }

        foreach ($relationships as $relationship) {
            array_push($this->_with, $relationship);
        }

        return $this;
    }

    /**
     * Calls methods from this class in underscore case.
     *
     * @param  string $method
     * @param  mixed  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $method = camelize($method);
        $result = $this;

        if (method_exists($this, $method)) {
            $class = [$this, $method];
            
            $result = call_user_func_array($class, $parameters);
        }

        return $result;
    }
}
