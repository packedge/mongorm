<?php namespace Packedge\Mongorm;

use League\Monga;

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

    public function first($columns = [])
    {
        $result = $this->getCollection()->find($this->query, $columns)->limit(1)->toArray();

        return count($result) === 0 ? null : $result;
    }
}