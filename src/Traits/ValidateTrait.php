<?php

namespace Rougin\Wildfire\Traits;

/**
 * Validate Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait ValidateTrait
{
    /**
     * @var array
     */
    protected $errors = array();

    /**
     * Returns a listing of error messages.
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Validates the specified data based on the validation rules.
     *
     * @param  array $data
     * @return boolean
     */
    public function validate(array $data = array())
    {
        $this->load->library('form_validation');

        ! empty($data) && $this->form_validation->set_data($data);

        $this->form_validation->set_rules($this->validation_rules);

        if (! ($validated = $this->form_validation->run())) {
            $this->errors = $this->form_validation->error_array();
        }

        return $validated;
    }

    /**
     * Returns a listing of error messages.
     * NOTE: To be removed in v1.0.0. Use $this->errors instead.
     *
     * @return array
     */
    public function validation_errors()
    {
        return $this->errors();
    }
}
