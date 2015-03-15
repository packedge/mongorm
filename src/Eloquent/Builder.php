<?php namespace Packedge\Mongorm\Eloquent;

use League\Monga;
use MongoCursorException;
use Packedge\Mongorm\Query\Builder as QueryBuilder;

/**
 * Class Builder
 * @package Packedge\Mongorm
 */
class Builder
{
    use ConvertableTrait;

    /**
     * Holds the query to search with
     *
     * @var array
     */
    protected $query = [];

    /**
     * Has the user set the query manually
     *
     * @var bool
     */
    protected $isRawQuery = false;

    /**
     * Holds the columns to return
     *
     * @var array
     */
    protected $columns = [];

    /**
     * Limit the results by this amount
     * -1 means no limit
     *
     * @var int
     */
    protected $limit = -1;

    /**
     * Holds a QueryBuilder instance
     *
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * Holds a Monga Connection instance
     *
     * @var \League\Monga\Connection
     */
    protected $monga;

    /**
     * Holds the current Model
     *
     * @var Model
     */
    protected $model;

    /**
     * Create a new instance of the builder
     *
     * @param Monga $monga
     * @param QueryBuilder $queryBuilder
     */
    public function __construct( Monga $monga = null, QueryBuilder $queryBuilder = null )
    {
        $monga = $monga ?: new Monga;
        $this->monga = $monga->connection();

        $queryBuilder = $queryBuilder ?: new QueryBuilder;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Get the current database connection
     *
     * @return \League\Monga\Database
     */
    protected function getDatabase()
    {
        // TODO: load from env/config
        return $this->monga->database( 'example' );
    }

    /**
     * Get the models collection
     *
     * @return \League\Monga\Collection
     */
    protected function getCollection()
    {
        return $this->getDatabase()->collection( $this->model->getCollectionName() );
    }

    /**
     * Executes the query based on the current query & requested columns
     *
     * @return mixed
     */
    protected function doQuery()
    {
        return $this->getCollection()->find( $this->generateQueryString(), $this->columns );
    }

    /**
     * Sets the builders' model
     *
     * @param CoreModel $model
     */
    public function setModel( CoreModel $model )
    {
        $this->model = $model;
    }

    /**
     * Gets the builders' model
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get the first result
     *
     * @param array $columns
     * @return Collection|null
     */
    public function first( $columns = [] )
    {
        if (!empty($columns))
        {
            $this->select( $columns );
        }

        $result = $this->getCollection()->findOne( $this->generateQueryString(), $this->columns );

        if (is_null( $result ))
        {
            return;
        }

        $result = $this->getStandardisedAttribute( [$result], 0 );

        return Collection::make( $result );
    }

    /**
     * Add a search term to the query
     *
     * @param string $column
     * @param string|null $operator
     * @param string|null $value
     * @return $this
     */
    public function where( $column, $operator = null, $value = null )
    {
        if ($this->isRawQuery)
        {
            return $this;
        }

        $part = $this->queryBuilder->parse( $column, $operator, $value );

        $position = $this->getQueryPosition();
        $this->query[$position] = array_merge( $this->query[$position], $part );

        return $this;
    }

    /**
     * Manually set the query
     *
     * @param $query
     * @return $this
     */
    public function whereRaw( $query )
    {
        $this->query = $query;
        $this->isRawQuery = true;

        return $this;
    }

    public function orWhere( $column, $operator = null, $value = null )
    {
        if ($this->isRawQuery)
        {
            return $this;
        }

        $part = $this->queryBuilder->parse( $column, $operator, $value );

        $this->query[count( $this->query )] = $part;

        return $this;
    }

    /**
     * Select a set of columns
     *
     * @param array $columns
     * @return $this
     */
    public function select( array $columns )
    {
        if (empty($columns) || $columns[0] === '*')
        {
            $this->columns = [];

            return $this;
        }

        foreach ($columns as $column)
        {
            $this->columns[$column] = true;
        }

        return $this;
    }

    /**
     * Get the results and format them
     *
     * @return Collection|null
     */
    public function get()
    {
        // TODO: use pagination etc.
        $results = $this->doQuery();

        if ($this->limit !== -1)
        {
            $results->limit( $this->limit );
        }

        $results = $results->toArray();

        if (!count( $results ))
        {
            return;
        }

        foreach ($results as $key => &$result)
        {
            $result = $this->getStandardisedAttribute( $results, $key );
        }

        return Collection::make( $results );
    }

    /**
     * Limit the amount of documents returned
     *
     * @param int $amount
     * @return $this
     */
    public function take( $amount )
    {
        $this->limit = $amount;

        return $this;
    }

    /**
     * Count the returned documents
     *
     * @return mixed
     */
    public function count()
    {
        return $this->doQuery()->count();
    }

    /**
     * Insert data into the collection
     *
     * @param array $data
     * @throws MongoCursorException
     * @throws \Exception
     * @return bool
     */
    public function insert( array $data )
    {
        return $this->getCollection()->insert( $data );
    }

    /**
     * Update existing documents from the collection
     *
     * @param array $data
     * @throws MongoCursorException
     * @throws \Exception
     * @return bool
     */
    public function update( array $data )
    {
        return $this->getCollection()->update( $data, $this->generateQueryString(), ['multiple' => true] );
    }

    /**
     *
     * Delete documents from the collection
     * @throws MongoCursorException
     * @throws \Exception
     * @return mixed
     */
    public function delete()
    {
        return $this->getCollection()->remove( $this->generateQueryString(), ['multiple' => true] );
    }

    private function getQueryPosition()
    {
        $position = count( $this->query );

        if ($position === 0)
        {
            $this->query[0] = [];

            return $position;
        }

        return $position - 1;
    }

    public function generateQueryString()
    {
        if ($this->isRawQuery)
        {
            return $this->query;
        }

        $sectionsCount = count( $this->query );

        if ($sectionsCount === 0)
        {
            return [];
        }

        if ($sectionsCount === 1)
        {
            return $this->query[0];
        }

        $sections = [];

        foreach ($this->query as $section)
        {
            if (count( $section ) > 1)
            {
                $sections[] = ['$and' => $section];
            } else
            {
                $sections[] = $section;
            }
        }

        return ['$or' => $sections];
    }
}
