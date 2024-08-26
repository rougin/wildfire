<?php

namespace Rougin\Wildfire;

/**
 * @package Wildfire
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Model extends \CI_Model
{
    /**
     * The model's attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = array();

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = array('id' => 'integer');

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var string[]
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
     * @var array<string, mixed>
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
     * @var string[]
     */
    protected $visible = array();

    /**
     * Initializes the model instance.
     *
     * @param array<string, mixed> $attributes
     */
    public function __construct(array $attributes = array())
    {
        $casts = (array) array('id' => 'integer');

        $this->casts = array_merge($casts, $this->casts);

        $this->original = (array) $attributes;

        foreach ($attributes as $key => $value)
        {
            $casted = $this->cast($key, $value);

            $this->attributes[$key] = $casted;
        }

        $keys = array_keys($this->original);

        $this->visible = $this->visible ?: $keys;
    }

    /**
     * Returns the attribute or from \CI_Model::__get.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (! array_key_exists($key, $this->attributes))
        {
            return parent::__get((string) $key);
        }

        $value = $this->attributes[(string) $key];

        $method = 'get_' . $key . '_attribute';

        $exists = method_exists($this, $method);

        return $exists ? $this->{$method}() : $value;
    }

    /**
     * Returns the model as a JSON string.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) json_encode($this->data());
    }

    /**
     * Returns an array of column names.
     *
     * @return string[]
     */
    public function columns()
    {
        return array_diff($this->visible, $this->hidden);
    }

    /**
     * Returns the attributes as an array.
     *
     * @return array<string, mixed>
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

    /**
     * Returns the name of the table.
     *
     * @return string
     */
    public function table()
    {
        return $this->table;
    }

    /**
     * Casts an attribute to a native PHP type.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function cast($key, $value)
    {
        // Parse the type from the property -----
        $type = trim(strtolower((string) ''));

        if (array_key_exists($key, $this->casts))
        {
            $type = $this->casts[$key];
        }

        $type = trim(strtolower($type));
        // --------------------------------------

        if ($type === 'boolean')
        {
            return (bool) $value;
        }

        if ($type === 'integer')
        {
            /** @var integer $value */
            return (int) $value;
        }

        return $value;
    }
}
