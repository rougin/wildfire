<?php

class Post extends Rougin\Wildfire\CodeigniterModel
{
    use Rougin\Wildfire\Traits\PaginateTrait;

    /**
     * Defines an inverse one-to-one or many relationship.
     *
     * @var array
     */
    protected $belongs_to = array('user');

    /**
     * Columns that will be displayed.
     * If not set, it will get the columns from the database table.
     *
     * @var array
     */
    protected $columns = array('id', 'name', 'user_id');

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post';

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
