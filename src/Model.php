<?php

namespace Rougin\Wildfire;

/**
 * Model
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Model extends \CI_Model
{
    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = array();

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primary = 'id';

    /**
     * The model attribute's original state.
     *
     * @var array
     */
    protected $original = array();

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '';

    /**
     * The attributes that should be visible for serialization.
     *
     * @var array
     */
    protected $visible = array();

    /**
     * Initializes the model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        $this->attributes = $this->original = $attributes;

        $keys = (array) array_keys($this->original);

        $this->visible = $this->visible ?: (array) $keys;
    }

    /**
     * Returns the attribute or the __get from \CI_Model.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->attributes[$key]) === true) {
            $value = $this->attributes[(string) $key];

            $method = 'get_' . $key . '_attribute';

            $exists = method_exists($this, $method);

            return $exists ? $this->{$method}() : $value;
        }

        return parent::__get((string) $key);
    }

    /**
     * Returns the model as a JSON string.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->data());
    }

    /**
     * Returns an array of column names.
     *
     * @return array
     */
    public function columns()
    {
        return array_diff($this->visible, $this->hidden);
    }

    /**
     * Returns the attributes as an array.
     *
     * @return array
     */
    public function data()
    {
        $flipped = array_flip((array) $this->columns());

        $values = (array) $this->attributes;

        return array_intersect_key($values, $flipped);
    }

    /**
     * Returns the primary key.
     *
     * @return string
     */
    public function primary()
    {
        return $this->primary;
    }
}
