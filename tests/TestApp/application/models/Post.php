<?php

class Post extends CI_Model
{
    /**
     * Columns that will be displayed.
     * If not set, it will get the columns from the database table.
     *
     * @var array
     */
    public $columns = array('id', 'name', 'user_id');

    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'post';

    /**
     * Gets the ID.
     *
     * @return integer
     */
    public function get_id()
    {
        return $this->id;
    }
}
