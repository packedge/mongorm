<?php namespace Builder;

use Mockery as m;
use Packedge\Mongorm\Eloquent\Builder;

class GetTest extends \TestCase
{
    protected function initaliseBuilder($data, $limit = -1)
    {
        $monga = m::mock('\League\Monga');
        $connection = m::mock('\League\Monga\Connection');
        $database = m::mock('\League\Monga\Database');
        $collection = m::mock('\League\Monga\Collection');
        $cursor = m::mock('\League\Monga\Cursor');
        $model = m::mock('\Packedge\Mongorm\Eloquent\Model');

        $cursor->shouldReceive('toArray')
            ->andReturn($data);
        $cursor->shouldReceive('skip')
               ->andReturnSelf();
        if ($limit > -1) {
            $cursor->shouldReceive('limit', $limit)
                ->andReturn($cursor);
        }
        $collection->shouldReceive('find', [[], []])
            ->andReturn($cursor);
        $database->shouldReceive('collection', ['users'])
            ->andReturn($collection);
        $connection->shouldReceive('database', ['example'])
            ->andReturn($database);
        $monga->shouldReceive('connection')
            ->andReturn($connection);
        $model->shouldReceive('getCollectionName')
            ->andReturn('users');

        $builder = new Builder($monga);
        $builder->setModel($model);

        return $builder;
    }

    /**
     * @test
     */
    public function it_gets_the_results()
    {
        $builder = $this->initaliseBuilder([
            [
                'first' => 'Fred',
                'email' => 'fred@gmail.com',
            ],
            ['first' => 'Alfred'],
            ['email' => 'i@email.com'],
            ['first' => 'Name', 'email' => 'email@address.com'],
        ]);
        $results = $builder->get();

        $this->assertCount(4, $results);
        $this->assertArrayHasKey('first', $results[1]);
        $this->assertArrayNotHasKey('email', $results[1]);
    }

    /**
     * @test
     */
    public function it_gets_the_results_with_a_limit()
    {
        $builder = $this->initaliseBuilder([
            [
                'first' => 'Fred',
                'email' => 'fred@gmail.com',
            ],
            ['first' => 'Alfred'],
        ], 2);
        $results = $builder->take(2)->get();

        $this->assertCount(2, $results);
    }
}
