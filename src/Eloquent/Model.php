<?php namespace Packedge\Mongorm\Eloquent;

abstract class Model extends CoreModel
{
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

    public static function all($columns = ['*'])
    {
        $instance = new static;
        return $instance->newBuilder()->select($columns)->get();
    }
}