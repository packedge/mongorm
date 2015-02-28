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
        // If the model already exists in the database we can just update our record
        // that is already in this database using the current IDs in this "where"
        // clause to only update this model. Otherwise, we'll just insert them.
        if ($this->exists) {
            $saved = $this->newBuilder()->where('_id', $this->convertToMongoId($this->attributes['_id']))->update($this->attributes);
        }
        // If the model is brand new, we'll insert it into our database and set the
        // ID attribute on the model to the value of the newly inserted row's ID
        // which is typically an auto-increment value managed by the database.
        else {
            $saved = $this->newBuilder()->insert($this->attributes);
        }

        // TODO: fill() attributes will new or updated values.

        return $saved;
    }
}
