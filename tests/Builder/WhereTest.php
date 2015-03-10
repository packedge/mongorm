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
    public function it_generates_a_query_with_one_section_and_one_part()
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

    /**
     * @test
     */
    public function it_generates_a_query_with_one_section_and_multiple_parts()
    {
        $query = m::mock( QueryBuilder::class );
        $query->shouldReceive( 'parse' )
              ->once()
              ->with( 'first', 'John', null )
              ->andReturn( ['first' => 'John'] );
        $query->shouldReceive( 'parse' )
              ->once()
              ->with( 'last', 'Smith', null )
              ->andReturn( ['last' => 'Smith'] );
        $query->shouldReceive( 'parse' )
              ->once()
              ->with( 'email', 'john@smith.com', null )
              ->andReturn( ['email' => 'john@smith.com'] );

        $this->initialiseBuilder( $query );
        $this->builder->where( 'first', 'John' )
                      ->where( 'last', 'Smith' )
                      ->where( 'email', 'john@smith.com' );
        $output = $this->builder->generateQueryString();

        $this->assertArrayHasKey( 'first', $output );
        $this->assertArrayHasKey( 'last', $output );
        $this->assertArrayHasKey( 'email', $output );

        $this->assertEquals( 'John', $output['first'] );
        $this->assertEquals( 'Smith', $output['last'] );
        $this->assertEquals( 'john@smith.com', $output['email'] );
    }

    /**
     * @test
     */
    public function it_generates_a_query_with_multiple_section_and_one_part()
    {
        $query = m::mock( QueryBuilder::class );
        $query->shouldReceive( 'parse' )
              ->once()
              ->with( 'first', 'John', null )
              ->andReturn( ['first' => 'John'] );
        $query->shouldReceive( 'parse' )
              ->once()
              ->with( 'first', 'Steve', null )
              ->andReturn( ['first' => 'Steve'] );

        $this->initialiseBuilder( $query );
        $this->builder->where( 'first', 'John' )
                      ->orWhere( 'first', 'Steve' );
        $output = $this->builder->generateQueryString();

        $this->assertArrayHasKey( '$or', $output );
        $this->assertArrayHasKey( 0, $output['$or'] );
        $this->assertArrayHasKey( 1, $output['$or'] );
        $this->assertArrayHasKey( 'first', $output['$or'][0] );
        $this->assertArrayHasKey( 'first', $output['$or'][1] );

        $this->assertEquals( 'John', $output['$or'][0]['first'] );
        $this->assertEquals( 'Steve', $output['$or'][1]['first'] );
    }

    /**
     * @test
     */
    public function it_generates_a_query_with_multiple_section_and_multiple_parts()
    {
        $query = m::mock( QueryBuilder::class );
        $query->shouldReceive( 'parse' )
              ->once()
              ->with( 'first', 'John', null )
              ->andReturn( ['first' => 'John'] );
        $query->shouldReceive( 'parse' )
              ->once()
              ->with( 'first', 'Steve', null )
              ->andReturn( ['first' => 'Steve'] );
        $query->shouldReceive( 'parse' )
              ->once()
              ->with( 'last', 'Smith', null )
              ->andReturn( ['last' => 'Smith'] );
        $query->shouldReceive( 'parse' )
              ->once()
              ->with( 'last', 'Mcqueen', null )
              ->andReturn( ['last' => 'Mcqueen'] );

        $this->initialiseBuilder( $query );
        $this->builder->where( 'first', 'John' )
                      ->where( 'last', 'Smith' )
                      ->orWhere( 'first', 'Steve' )
                      ->where( 'last', 'Mcqueen' );
        $output = $this->builder->generateQueryString();

        $this->assertArrayHasKey( '$or', $output );
        $this->assertArrayHasKey( 0, $output['$or'] );
        $this->assertArrayHasKey( 1, $output['$or'] );
        $this->assertArrayHasKey( '$and', $output['$or'][0] );
        $this->assertArrayHasKey( '$and', $output['$or'][1] );
        $this->assertArrayHasKey( 'first', $output['$or'][0]['$and'] );
        $this->assertArrayHasKey( 'last', $output['$or'][0]['$and'] );
        $this->assertArrayHasKey( 'first', $output['$or'][1]['$and'] );
        $this->assertArrayHasKey( 'last', $output['$or'][1]['$and'] );

        $this->assertEquals( 'John', $output['$or'][0]['$and']['first'] );
        $this->assertEquals( 'Smith', $output['$or'][0]['$and']['last'] );
        $this->assertEquals( 'Steve', $output['$or'][1]['$and']['first'] );
        $this->assertEquals( 'Mcqueen', $output['$or'][1]['$and']['last'] );
    }
}
