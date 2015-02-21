<?php namespace Packedge\Mongorm\Eloquent;


use Carbon\Carbon;
use DateTime;

trait MutableTrait
{
    use ConvertableTrait;
    /**
     * The models attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The models attributes original state.
     *
     * @var array
     */
    protected $original = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Get an attribute for the model.
     *
     * @param $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $inAttributes = array_key_exists($key, $this->attributes);

        // If the key references an attribute, we can just go ahead and return the
        // plain attribute value from the model. This allows every attribute to
        // be dynamically accessed through the __get method without accessors.
        if ($inAttributes || $this->hasGetMutator($key))
        {
            return $this->getAttributeValue($key);
        }
    }

    public function getStandardisedAttribute($key)
    {
        $value = $this->getAttributeFromArray($key);

        // If the value is an array, recursively standardise all its values.
        if(is_array($value))
        {
            $data = [];
            foreach($value as $subkey => $item)
            {
                $data[] = $this->getStandardisedAttribute($subkey);
            }
            $value = $data;
        }

        // Automatically convert Mongo data types into standard PHP types.
        if($this->isMongoType($value))
        {
            $value = $this->convertMongoType($value);
        }

        return $value;
    }

    public function hasCast()
    {
        // TODO: fix
        return false;
    }

    public function castAttribute($key, $value)
    {
        
    }

    public function getAttributeValue($key)
    {
        $value = $this->getStandardisedAttribute($key);

        // If the attribute has a get mutator, we will call that then return what
        // it returns as the value, which is useful for transforming values on
        // retrieval from the model to a form that is more useful for usage.
        if ($this->hasGetMutator($key))
        {
            return $this->mutateAttribute($key, $value);
        }

        // If the attribute exists within the cast array, we will convert it to
        // an appropriate native PHP type dependant upon the associated value
        // given with the key in the pair. Dayle made this comment line up.
        if ($this->hasCast($key))
        {
            $value = $this->castAttribute($key, $value);
        }
        // If the attribute is listed as a date, we will convert it to a DateTime
        // instance on retrieval, which makes it quite convenient to work with
        // date fields without having to create a mutator for each property.
        elseif (in_array($key, $this->getDates()))
        {
            if ($value) return $this->asDateTime($value);
        }


        return $value;
    }

    /**
     * Get the attributes that should be converted to dates.
     *
     * @return array
     */
    public function getDates()
    {
        $defaults = array(static::CREATED_AT, static::UPDATED_AT);

        return array_merge($this->dates, $defaults);
    }

    /**
     * Return a timestamp as DateTime object.
     *
     * @param  mixed  $value
     * @return \Carbon\Carbon
     */
    protected function asDateTime($value)
    {
        // If this value is an integer, we will assume it is a UNIX timestamp's value
        // and format a Carbon object from this timestamp. This allows flexibility
        // when defining your date fields as they might be UNIX timestamps here.
        if (is_numeric($value))
        {
            return Carbon::createFromTimestamp($value);
        }

        // If the value is in simply year, month, day format, we will instantiate the
        // Carbon instances from that format. Again, this provides for simple date
        // fields on the database, while still supporting Carbonized conversion.
        elseif (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value))
        {
            return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        }

        return Carbon::instance($value);
    }

    /**
     * Get an attribute from the $attributes array.
     *
     * @param  string  $key
     * @return mixed
     */
    protected function getAttributeFromArray($key)
    {
        if (array_key_exists($key, $this->attributes))
        {
            return $this->attributes[$key];
        }
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasGetMutator($key)
    {
        return method_exists($this, 'get' . studly_case($key) . 'Attribute');
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        return $this->{'get'.studly_case($key).'Attribute'}($value);
    }

    /**
     * Get all of the current attributes on the model.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set a given attribute on the model.
     *
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value)
    {
        // First we will check for the presence of a mutator for
        // the set operation which simply lets the developers
        // tweak the attribute as it is set on the model.
        if($this->hasSetMutator($key))
        {
            $method = 'set'.studly_case($key).'Attribute';

            return $this->{$method}($value);
        }

        // TODO: handle dates

        // TODO: handle casts/datatypes

        $this->attributes[$key] = $value;
    }

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasSetMutator($key)
    {
        return method_exists($this, 'set' . studly_case($key) . 'Attribute');
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }
} 