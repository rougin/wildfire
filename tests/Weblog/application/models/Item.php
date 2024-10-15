<?php

use Rougin\Wildfire\Model;
use Rougin\Wildfire\Traits\WildfireTrait;
use Rougin\Wildfire\Traits\WritableTrait;

/**
 * @property string     $name
 * @property \CI_Loader $load
 */
class Item extends Model
{
    use WildfireTrait;
    use WritableTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'items';
}
