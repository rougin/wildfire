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
