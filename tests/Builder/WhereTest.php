<?php namespace Builder;

use League\Monga;
use League\Monga\Connection;
use Mockery as m;
use Packedge\Mongorm\Eloquent\Builder;
use Packedge\Mongorm\Query\Builder as QueryBuilder;

class WhereTest extends \TestCase
{
    /**
     * @var Builder
     */
    protected $builder;

    public function initialiseBuilder( $query = null )
    {
        $monga = m::mock( Monga::class );
        $collection = m::mock( Connection::class );
        $monga->shouldReceive( 'connection' )
              ->once()
              ->andReturn( $collection );
        $queryBuilder = $query ?: m::mock( QueryBuilder::class );
        $this->builder = new Builder( $monga, $queryBuilder );
    }

    /**
     * @test
     */
    public function it_generates_an_empty_query()
    {
        $this->initialiseBuilder();
        $output = $this->builder->generateQueryString();

        $this->assertEquals( [], $output );
    }

    /**
     * @test
     */
    public function it_generates_a_query_with_one_part()
    {
        $query = m::mock( QueryBuilder::class );
        $query->shouldReceive( 'parse' )
              ->once()
              ->with( 'last', 'Smith', null )
              ->andReturn( ['last' => 'Smith'] );
        $this->initialiseBuilder( $query );
        $this->builder->where( 'last', 'Smith' );
        $output = $this->builder->generateQueryString();

        $this->assertEquals( ['last' => 'Smith'], $output );
    }
}
