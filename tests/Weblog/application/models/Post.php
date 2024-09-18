<?php

class Post extends Rougin\Wildfire\Model
{
    use Rougin\Wildfire\Traits\PaginateTrait;

    /**
     * The attributes that should be visible for serialization.
     *
     * @var string[]
     */
    protected $hidden = array('message');

    /**
     * Additional configuration to Pagination Class.
     *
     * @link https://codeigniter.com/userguide3/libraries/pagination.html?highlight=pagination#customizing-the-pagination
     *
     * @var array<string, mixed>
     */
    protected $pagee = array();

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
    public function get_id_attribute()
    {
        /** @var integer */
        $id = $this->attributes['user_id'];

        return (int) $id;
    }
}
