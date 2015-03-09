<?php namespace Builder;

use Mockery as m;
use Packedge\Mongorm\Eloquent\Builder;

class CountTest extends \TestCase
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

    /**
     * @test
     */
    public function it_gets_the_count()
    {
        $builder = $this->initaliseBuilder(5);
        $count = $builder->count();

        $this->assertEquals(5, $count);
    }
}
