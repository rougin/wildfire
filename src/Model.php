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
     * Model's current attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = array();

    /**
     * Attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = array('id' => 'integer');

    /**
     * Attributes that should be hidden for serialization.
     *
     * @var string[]
     */
    protected $hidden = array();

    /**
     * Primary key for the model.
     *
     * @var string
     */
    protected $primary = 'id';

    /**
     * Model attribute's original state.
     *
     * @var array<string, mixed>
     */
    protected $original = array();

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = '';

    /**
     * Allows usage of timestamp fields ("created_at", "updated_at").
     *
     * @var boolean
     */
    protected $timestamps = true;

    /**
     * Attributes that should be visible for serialization.
     *
     * @var string[]
     */
    protected $visible = array();

    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(array $attributes = array())
    {
        $casts = array('id' => 'integer');

        $this->casts = array_merge($casts, $this->casts);

        $this->original = $attributes;

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
        $flipped = array_flip($this->columns());

        $values = $this->attributes;

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
