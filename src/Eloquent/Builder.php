<?php namespace Packedge\Mongorm\Eloquent;

use League\Monga;
use Packedge\Mongorm\Query\Builder as QueryBuilder;
/**
 * Class Builder
 * @package Packedge\Mongorm
 */
class Builder
{
    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

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
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(Monga $monga = null, QueryBuilder $queryBuilder = null)
    {
        $monga = $monga ?: new Monga;
        $this->monga = $monga->connection();

        $queryBuilder = $queryBuilder ?: new QueryBuilder;
        $this->queryBuilder = $queryBuilder;
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
     * @param string $column
     * @param string|null $operator
     * @param string|null $value
     */
    public function where($column, $operator = null, $value = null)
    {
        $part = $this->queryBuilder->parse($column, $operator, $value);
        $this->query = array_merge($this->query, $part);
    }
}