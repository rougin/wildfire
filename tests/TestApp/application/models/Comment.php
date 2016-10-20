<?php

class Comment extends Rougin\Wildfire\CodeigniterModel
{
    use Rougin\Wildfire\Traits\ValidateTrait;

    /**
     * Columns that will be hidden in the display.
     * If not set, it will hide a "password" column if it exists.
     *
     * @var array
     */
    protected $hidden = array('id');

    /**
     * An array of validation rules. This needs to be the same format
     * as validation rules passed to the Form_validation library.
     *
     * @var array
     */
    protected $validation_rules = array(
        array('field' => 'name', 'label' => 'Name', 'rules' => 'required'),
    );
}
