<?php

class Comment extends Rougin\Wildfire\Model
{
    use Rougin\Wildfire\Traits\ValidateTrait;

    /**
     * An array of validation rules. This needs to be the same format
     * as validation rules passed to the Form_validation library.
     *
     * @var array
     */
    protected $rules = array(
        array('field' => 'name', 'label' => 'Name', 'rules' => 'required'),
    );
}
