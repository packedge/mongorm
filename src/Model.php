<?php namespace Packedge\Mongorm;


use ArrayAccess;
use Illuminate\Support\Str;
use ReflectionClass;

abstract class Model implements ArrayAccess
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * The collection associated with the model.
     *
     * @var String
     */
    protected $collection;

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
     * Returns a new instance of the Builder.
     *
     * @return Builder
     */
    public function newBuilder()
    {
        $builder = new Builder;
        $builder->setModel($this);
        
        return $builder;
    }

    /**
     * Get or guess the collection name.
     *
     * @return string
     */
    public function getCollectionName()
    {
        if(isset($this->collection)) return $this->collection;

        $reflector = new ReflectionClass($this);
        $str = new Str;
        return $str->lower(
            $str->plural(
                $str->snake(
                    $reflector->getShortName()
                )
            )
        );
    }

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    /**
     * Set the primary key for the model.
     *
     * @param  string  $key
     * @return void
     */
    public function setKeyName($key)
    {
        $this->primaryKey = $key;
    }

    /**
     * Get the first document from the database.
     *
     * @param array $columns
     * @return mixed
     */
    public static function first($columns = [])
    {
        $instance = new static;

        return $instance->newBuilder()->first($columns);
    }

    /**
     * Get an attribute for the model.
     *
     * @param $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        // First we will check for the presence of a mutator for
        // the get operation which simply lets the developers
        // tweak the attribute as it is get on the model.
        if($this->hasGetMutator($key))
        {
            $value = $this->getAttributeFromArray($key);

            return $this->mutateAttribute($key, $value);
        }

        // TODO: handle dates

        // TODO: handle casts/datatypes

        return $this->getAttributeFromArray($key);

        // TODO: will need to check for method existing for relationships
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