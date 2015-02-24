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
        return static::instance()->newBuilder()->first($columns);
    }

    public static function all($columns = ['*'])
    {
        // TODO: should return a collection of models.
        return static::instance()->newBuilder()->select($columns)->get();
    }

    public static function find($id)
    {
        // TODO: should return a model with attributes filled.
        $instance = static::instance();
        return $instance->newBuilder()->where('_id', '=', $instance->convertToMongoId($id))->get();
    }

    public function save()
    {
        $this->newBuilder()->update($this->attributes);
    }
}