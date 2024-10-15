<?php

use Rougin\Wildfire\Model;
use Rougin\Wildfire\Traits\ValidateTrait;

class Comment extends Model
{
    use ValidateTrait;

    /**
     * An array of validation rules. This needs to be the same format
     * as validation rules passed to the Form Validation library.
     *
     * @link https://codeigniter.com/userguide3/libraries/form_validation.html#setting-rules-using-an-array
     *
     * @var array<string, string>[]
     */
    protected $rules = array(
        array('field' => 'name', 'label' => 'Name', 'rules' => 'required'),
    );
}
