<?php

namespace Rougin\Wildfire;

use Rougin\Wildfire\Helpers\InstanceHelper;

/**
 * Codeigniter Model
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 *
 * @property \CI_DB_query_builder $db
 * @method   array result()
 */
class CodeigniterModel extends \CI_Model
{
    use Traits\ModelTrait, Traits\RelationshipTrait;

    public function __construct()
    {
        parent::__construct();

        InstanceHelper::create($this->db);
    }

    /**
     * Returns all of the models from the database.
     *
     * @return array
     */
    public function all()
    {
        return $this->find_by([]);
    }

    /**
     * A wrapper to $this->db->count_all().
     *
     * @return integer
     */
    public function countAll()
    {
        return $this->db->count_all($this->getTableName());
    }

    /**
     * Deletes the specified ID of the model from the database.
     *
     * @param  integer $id
     * @return void
     */
    public function delete($id)
    {
        $this->db->where($this->getPrimaryKey(), $id);

        return $this->db->delete($this->getTableName());
    }

    /**
     * Finds the specified model from the database.
     *
     * @param  integer $id
     * @return mixed
     */
    public function find($id)
    {
        return InstanceHelper::get()->find($this->getTableName(), $id);
    }

    /**
     * Finds the specified model from the database by the given delimiters.
     *
     * @param  array $delimiters
     * @return mixed
     */
    public function find_by(array $delimiters)
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
        return InstanceHelper::get()->get($this);
    }

    /**
     * Inserts a new row into the table.
     *
     * @param  array $data
     * @return integer
     */
    public function insert(array $data)
    {
        $this->db->insert($this->getTableName(), $data);

        return $this->db->insert_id();
    }

    /**
     * A wrapper to $this->db->limit().
     *
     * @param  integer $value
     * @param  string  $offset
     * @return self
     */
    public function limit($value, $offset = '')
    {
        $this->db->limit($value, $offset);

        return $this;
    }

    /**
     * Updates the selected row from the table.
     *
     * @param  integer $id
     * @param  array   $data
     * @return boolean
     */
    public function update($id, array $data)
    {
        $this->db->where($this->getPrimaryKey(), $id);
        $this->db->set($data);

        return $this->db->update($this->getTableName());
    }
}
