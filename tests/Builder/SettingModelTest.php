<?php namespace Builder;

use Mockery as m;
use Packedge\Mongorm\Builder;

class SettingModelTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Builder
     */
    protected $builder;

    public function setUp()
    {
        $monga = m::mock('\League\Monga');
        $connection = m::mock('\League\Monga\Connection');

        $monga->shouldReceive('connection')
            ->andReturn($connection);

        $this->builder = new Builder($monga);
    }

    /**
     * @test
     */
    public function it_sets_the_model()
    {
        $model = m::mock('\Packedge\Mongorm\Model');

        $this->builder->setModel($model);

        $this->assertInstanceOf('\Packedge\Mongorm\Model', $this->builder->getModel());
    }
}
 