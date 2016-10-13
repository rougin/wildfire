<?php

class User extends Rougin\Wildfire\CodeigniterModel
{
    /**
     * Columns that will be displayed.
     * If not set, it will get the columns from the database table.
     *
     * @var array
     */
    protected $columns = array('name');

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
}
