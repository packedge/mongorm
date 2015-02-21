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
     * @var array
     */
    protected $columns = [];

    /**
     * @var int
     */
    protected $limit = -1;

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

    protected function doQuery()
    {
        return $this->getCollection()->find($this->query, $this->columns);
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
     * @return array|null
     */
    public function first($columns = [])
    {
        if (!empty($columns)) {
            $this->select($columns);
        }

        return $this->getCollection()->findOne($this->query, $columns);
    }

    /**
     * @param string $column
     * @param string|null $operator
     * @param string|null $value
     * @return $this
     */
    public function where($column, $operator = null, $value = null)
    {
        $part = $this->queryBuilder->parse($column, $operator, $value);
        $this->query = array_merge($this->query, $part);

        return $this;
    }

    /**
     * @param array $columns
     * @return $this
     */
    public function select(array $columns)
    {
        if (empty($columns) || $columns[0] === '*') {
            $this->columns = [];

            return $this;
        }

        foreach ($columns as $column) {
            $this->columns[$column] = true;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function get()
    {
        // TODO: use pagination etc.
        $results = $this->doQuery();

        if ($this->limit !== -1) {
            $results->limit($this->limit);
        }

        return $results->toArray();
    }

    /**
     * @param int $amount
     * @return $this
     */
    public function take($amount)
    {
        $this->limit = $amount;

        return $this;
    }

    public function count()
    {
        return $this->doQuery()->count();
    }
}