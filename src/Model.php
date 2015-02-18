<?php namespace Packedge\Mongorm;


use Illuminate\Support\Str;
use ReflectionClass;

abstract class Model
{
    /**
     * @var String
     */
    protected $collection;

    /**
     * @return Builder
     */
    protected function newBuilder()
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
}