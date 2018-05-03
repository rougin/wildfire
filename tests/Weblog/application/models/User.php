<?php

class User extends Rougin\Wildfire\Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = array('age' => 'integer', 'accepted' => 'boolean');

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
}
