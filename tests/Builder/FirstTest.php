<?php namespace Builder;

use Mockery as m;
use Packedge\Mongorm\Eloquent\Builder;

class BuilderFirstTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Builder
     */
    protected $builder;

    public function setUp()
    {
        $monga = m::mock('\League\Monga');
        $connection = m::mock('\League\Monga\Connection');
        $database = m::mock('\League\Monga\Database');
        $collection = m::mock('\League\Monga\Collection');
        $data = [
            'first' => 'Fred',
            'email' => 'fred@gmail.com'
        ];

        $collection->shouldReceive('findOne', [[], []])
            ->andReturn($data);
        $database->shouldReceive('collection', ['users'])
            ->andReturn($collection);
        $connection->shouldReceive('database', ['example'])
            ->andReturn($database);
        $monga->shouldReceive('connection')
            ->andReturn($connection);

        $this->builder = new Builder($monga);

        $model = m::mock('\Packedge\Mongorm\Eloquent\Model');
        $model->shouldReceive('getCollectionName')
            ->andReturn('users');
        $this->builder->setModel($model);
    }

    /**
     * @test
     */
    public function it_gets_the_first_item()
    {
        $first = $this->builder->first();

        $this->assertEquals('Fred', $first['first']);
        $this->assertEquals('fred@gmail.com', $first['email']);
    }
}
 