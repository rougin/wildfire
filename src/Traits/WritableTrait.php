<?php

namespace Rougin\Wildfire\Traits;

/**
 * @codeCoverageIgnore
 *
 * TODO: Write tests for this trait.
 *
 * @property \CI_DB_query_builder $db
 *
 * @method string table()
 *
 * @package Wildfire
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
trait WritableTrait
{
    /**
     * Creates a new row of data to the database.
     *
     * @param array<string, mixed> $data
     *
     * @return boolean
     */
    public function create($data)
    {
        $input = $this->input($data);

        if ($this->timestamps)
        {
            $input['created_at'] = date('Y-m-d H:i:s');
        }

        $table = $this->table;

        return $this->db->insert($table, $input);
    }

    /**
     * Deletes the specified item from the database.
     *
     * @param integer $id
     *
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where($this->primary, $id);

        $result = $this->db->delete($this->table);

        return $result ? true : false;
    }

    /**
     * Checks if the specified data exists in the database.
     *
     * @param array<string, mixed> $data
     * @param integer|null         $id
     *
     * @return boolean
     */
    public function exists($data, $id = null)
    {
        // Specify logic here if applicable ---
        // ------------------------------------

        return false;
    }

    /**
     * Returns the total rows from the specified table.
     *
     * @return integer
     */
    public function total()
    {
        return $this->db->from($this->table)->count_all_results();
    }

    /**
     * Updates the specified data to the database.
     *
     * @param integer              $id
     * @param array<string, mixed> $data
     *
     * @return boolean
     */
    public function update($id, $data)
    {
        $input = $this->input($data, $id);

        if ($this->timestamps)
        {
            $input['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->where($this->primary, $id);

        return $this->db->update($this->table, $input);
    }

    /**
     * Updates the payload to be passed to the database.
     *
     * @param array<string, mixed> $data
     * @param integer|null         $id
     *
     * @return array<string, mixed>
     */
    protected function input($data, $id = null)
    {
        // List editable fields from table ---
        // -----------------------------------

        return $data;
    }
}
