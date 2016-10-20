<?php

namespace Rougin\Wildfire\Traits;

/**
 * Validate Trait
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 *
 * @property array $validation_rules
 */
trait ValidateTrait
{
    /**
     * @var array
     */
    protected $validationErrors = [];

    /**
     * Validates the specified data based on the validation rules.
     *
     * @param  array $data
     * @return boolean
     */
    public function validate(array $data = [])
    {
        $this->load->library('form_validation');

        if (! empty($data)) {
            $this->form_validation->set_data($data);
        }

        $this->form_validation->set_rules($this->validation_rules);

        $validated = $this->form_validation->run() === true;

        if (! $validated) {
            $this->validationErrors = $this->form_validation->error_array();
        }

        return $validated;
    }

    /**
     * Returns a listing of error messages.
     *
     * @return array
     */
    public function validation_errors()
    {
        return $this->validationErrors;
    }
}
