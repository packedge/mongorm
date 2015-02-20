<?php namespace Packedge\Mongorm;


use ArrayAccess;
use Illuminate\Support\Str;
use Packedge\Mongorm\Traits\MutableTrait;
use ReflectionClass;

abstract class Model implements ArrayAccess
{
    use MutableTrait;
    
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
}