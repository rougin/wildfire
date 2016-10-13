<?php

class User extends CI_Model
{
    /**
     * Columns that will be displayed.
     * If not set, it will get the columns from the database table.
     *
     * @var array
     */
    public $columns = array('name');
}
