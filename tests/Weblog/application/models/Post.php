<?php

class Post extends Rougin\Wildfire\Model
{
    /**
     * The attributes that should be visible for serialization.
     *
     * @var array
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
        return $this->attributes['user_id'];
    }
}
