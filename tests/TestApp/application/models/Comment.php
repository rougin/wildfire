<?php

class Comment extends Rougin\Wildfire\CodeigniterModel
{
    /**
     * Columns that will be hidden in the display.
     * If not set, it will hide a "password" column if it exists.
     *
     * @var array
     */
    protected $hidden = array('id');
}
