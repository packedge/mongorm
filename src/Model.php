<?php namespace Packedge\Mongorm;


use Illuminate\Support\Str;
use ReflectionClass;

abstract class Model
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
     * @return Builder
     */
    public function newBuilder()
    {
        $builder = new Builder;
        $builder->setModel($this);
        
        return $builder;
    }

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

    public static function first($columns = [])
    {
        $instance = new static;

        return $instance->newBuilder()->first($columns);
    }

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

    public function setAttribute($key, $value)
    {
        // TODO: check for mutator method

        // TODO: handle dates

        // TODO: handle casts/datatypes

        $this->attributes[$key] = $value;
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }
}