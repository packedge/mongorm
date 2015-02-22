<?php namespace Builder;

use Mockery as m;
use Packedge\Mongorm\Eloquent\Builder;

class CountTest extends \PHPUnit_Framework_TestCase
{

    protected function initaliseBuilder($data, $query = [])
    {
        $monga = m::mock('\League\Monga');
        $connection = m::mock('\League\Monga\Connection');
        $database = m::mock('\League\Monga\Database');
        $collection = m::mock('\League\Monga\Collection');
        $cursor = m::mock('\League\Monga\Cursor');
        $model = m::mock('\Packedge\Mongorm\Eloquent\Model');
        $queryBuilder = m::mock('\Packedge\Mongorm\Query\Builder');

        $queryBuilder->shouldReceive('parse', ['email', null, null])
            ->andReturn(['email' => ['$exists' => true]]);
        $cursor->shouldReceive('count')
            ->andReturn($data);
        $collection->shouldReceive('find', [$query, []])
            ->andReturn($cursor);
        $database->shouldReceive('collection', ['users'])
            ->andReturn($collection);
        $connection->shouldReceive('database', ['example'])
            ->andReturn($database);
        $monga->shouldReceive('connection')
            ->andReturn($connection);
        $model->shouldReceive('getCollectionName')
            ->andReturn('users');

        $builder = new Builder($monga, $queryBuilder);
        $builder->setModel($model);

        return $builder;
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function it_gets_the_count()
    {
        $builder = $this->initaliseBuilder(5);
        $count = $builder->count();

        $this->assertEquals(5, $count);
    }

    /**
     * @test
     */
    public function it_gets_the_count_where_column_exists()
    {
        $builder = $this->initaliseBuilder(3, ['email' => ['$exists' => true]]);
        $count = $builder->where('email')->count();

        $this->assertEquals(3, $count);
    }
}
 