<?php namespace Builder;

use Mockery as m;
use Packedge\Mongorm\Eloquent\Builder;

class FirstTest extends \PHPUnit_Framework_TestCase
{
    protected function initaliseBuilder($data, $columns = [])
    {
        $monga = m::mock('\League\Monga');
        $connection = m::mock('\League\Monga\Connection');
        $database = m::mock('\League\Monga\Database');
        $collection = m::mock('\League\Monga\Collection');
        $model = m::mock('\Packedge\Mongorm\Eloquent\Model');

        $collection->shouldReceive('findOne', [[], $columns])
            ->andReturn($data);
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

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function it_gets_the_first_item()
    {
        $builder = $this->initaliseBuilder([
            'first' => 'Fred',
            'email' => 'fred@gmail.com',
        ]);

        $first = $builder->first();

        $this->assertEquals('Fred', $first['first']);
        $this->assertEquals('fred@gmail.com', $first['email']);
    }

    /**
     * @test
     */
    public function it_gets_the_first_item_with_select()
    {
        $builder = $this->initaliseBuilder([
            'first' => 'Fred',
        ], [
            'first',
        ]);

        $first = $builder->select(['first'])->first();

        $this->assertEquals('Fred', $first['first']);
        $this->assertCount(1, $first);
    }

    /**
     * @test
     */
    public function it_gets_the_first_item_with_anything_select()
    {
        $builder = $this->initaliseBuilder([
            'first' => 'Fred',
            'email' => 'fred@gmail.com',
        ]);

        $first = $builder->select(['*'])->first();

        $this->assertEquals('Fred', $first['first']);
        $this->assertEquals('fred@gmail.com', $first['email']);
        $this->assertCount(2, $first);
    }

    /**
     * @test
     */
    public function it_gets_the_first_item_with_column()
    {
        $builder = $this->initaliseBuilder([
            'first' => 'Fred',
        ], [
            'first',
        ]);

        $first = $builder->first(['first']);

        $this->assertEquals('Fred', $first['first']);
        $this->assertCount(1, $first);
    }
}
