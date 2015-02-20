<?php namespace Packedge\Mongorm;

use League\Monga;

/**
 * Class Builder
 * @package Packedge\Mongorm
 */
class Builder
{
    /**
     * @var array
     */
    protected $query;

    /**
     * @var \League\Monga\Connection
     */
    protected $monga;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Monga $monga
     */
    public function __construct(Monga $monga = null)
    {
        $monga = $monga ?: new Monga;
        $this->monga = $monga->connection();
    }

    /**
     * @return \League\Monga\Database
     */
    protected function getDatabase()
    {
        // TODO: load from env/config
        return $this->monga->database('example');
    }

    /**
     * @return \League\Monga\Collection
     */
    protected function getCollection()
    {
        return $this->getDatabase()->collection($this->model->getCollectionName());
    }

    /**
     * @param Model $model
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $columns
     * @return null
     */
    public function first($columns = [])
    {
        $result = $this->getCollection()->find($this->query, $columns)->limit(1)->toArray();

        return count($result) === 0 ? null : $result;
    }

    /**
     * @param array $query
     */
    public function where(array $query)
    {
        // TODO: proper query builder
        $this->query = $query;
    }
}