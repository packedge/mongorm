<?php namespace Packedge\Mongorm;


use ArrayAccess;
use Illuminate\Support\Str;
use ReflectionClass;

abstract class Model implements ArrayAccess
{
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
        // TODO: check for mutator method

        // TODO: handle dates

        // TODO: handle casts/datatypes

        if(array_key_exists($key, $this->attributes))
        {
            return $this->attributes[$key];
        }

        // TODO: will need to check for method existing for relationships
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
        // TODO: check for mutator method

        // TODO: handle dates

        // TODO: handle casts/datatypes

        $this->attributes[$key] = $value;
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