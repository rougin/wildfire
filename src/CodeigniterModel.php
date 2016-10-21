<?php

namespace Rougin\Wildfire;

/**
 * Codeigniter Model
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 *
 * @property \CI_DB $db
 */
class CodeigniterModel extends \CI_Model
{
    use Traits\ModelTrait, Traits\RelationshipTrait;

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
        return Wildfire::create($this->db)->find($this->getTableName(), $id);
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
        return Wildfire::create($this->db)->get($this);
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
