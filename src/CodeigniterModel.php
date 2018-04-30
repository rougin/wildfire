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
    use Traits\ModelTrait, Traits\RelationshipTrait;

    /**
     * @var \Rougin\Wildfire\Wildfire
     */
    protected $wildfire;

    /**
     * Initializes the Codeigniter model instance.
     *
     * @param \Rougin\Wildfire\Wildfire $wildfire
     */
    public function __construct(Wildfire $wildfire = null)
    {
        $default = new Wildfire($this->db);

        $this->wildfire = $wildfire ?: $default;
    }

    /**
     * Returns all of the models from the database.
     *
     * @return array
     */
    public function all()
    {
        return $this->find_by(array());
    }

    /**
     * A wrapper to $this->db->count_all().
     *
     * @return integer
     */
    public function countAll()
    {
        return $this->db->count_all($this->table());
    }

    /**
     * Deletes the specified ID of the model from the database.
     *
     * @param  integer $id
     * @return void
     */
    public function delete($id)
    {
        $this->db->where($this->primary(), $id);

        return $this->db->delete($this->table());
    }

    /**
     * Finds the specified model from the database.
     *
     * @param  integer $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->wildfire->find($this->table(), $id);
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
        return $this->wildfire->get($this);
    }

    /**
     * Inserts a new row into the table.
     *
     * @param  array $data
     * @return integer
     */
    public function insert(array $data)
    {
        $table = (string) $this->table();

        $this->db->insert($table, $data);

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
     * Sets a property with a value.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return self
     */
    public function set($key, $value)
    {
        $this->$key = $value;

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
        $this->db->where($this->primary(), $id);

        $this->db->set((array) $data);

        return $this->db->update($this->table());
    }
}
