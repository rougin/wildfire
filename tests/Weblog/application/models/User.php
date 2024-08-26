<?php

/**
 * @property string     $name
 * @property \CI_Loader $load
 */
class User extends Rougin\Wildfire\Model
{
    use Rougin\Wildfire\Traits\WildfireTrait;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = array('age' => 'integer', 'accepted' => 'boolean');

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
}
