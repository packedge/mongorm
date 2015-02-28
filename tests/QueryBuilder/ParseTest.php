<?php namespace QueryBuilder;

use Packedge\Mongorm\Query\Builder as QueryBuilder;

class ParseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    public function setUp()
    {
        $this->queryBuilder = new QueryBuilder;
    }

    /**
     * @test
     */
    public function it_only_specifies_column()
    {
        $query = $this->queryBuilder->parse('email');

        $this->assertArrayHasKey('email', $query);
        $this->assertArrayHasKey('$exists', $query['email']);
        $this->assertTrue($query['email']['$exists']);
    }

    /**
     * @test
     */
    public function it_only_specifies_column_and_operator()
    {
        $query = $this->queryBuilder->parse('email', 'fred@gmail.com');

        $this->assertArrayHasKey('email', $query);
        $this->assertEquals('fred@gmail.com', $query['email']);
    }

    /**
     * @test
     */
    public function it_specifies_operator_as_equals()
    {
        $query = $this->queryBuilder->parse('email', '=', 'fred@gmail.com');

        $this->assertArrayHasKey('email', $query);
        $this->assertEquals('fred@gmail.com', $query['email']);
    }

    /**
     * @test
     */
    public function it_specifies_operator_as_not_equals()
    {
        $query = $this->queryBuilder->parse('email', '!=', 'fred@gmail.com');

        $this->assertArrayHasKey('email', $query);
        $this->assertArrayHasKey('$ne', $query['email']);
        $this->assertEquals('fred@gmail.com', $query['email']['$ne']);

        $query = $this->queryBuilder->parse('email', '<>', 'fred@gmail.com');

        $this->assertArrayHasKey('email', $query);
        $this->assertArrayHasKey('$ne', $query['email']);
        $this->assertEquals('fred@gmail.com', $query['email']['$ne']);
    }

    /**
     * @test
     */
    public function it_specifies_operator_as_greater_than()
    {
        $query = $this->queryBuilder->parse('age', '>', 20);

        $this->assertArrayHasKey('age', $query);
        $this->assertArrayHasKey('$gt', $query['age']);
        $this->assertEquals(20, $query['age']['$gt']);
    }

    /**
     * @test
     */
    public function it_specifies_operator_as_greater_than_equals()
    {
        $query = $this->queryBuilder->parse('age', '>=', 20);

        $this->assertArrayHasKey('age', $query);
        $this->assertArrayHasKey('$gte', $query['age']);
        $this->assertEquals(20, $query['age']['$gte']);
    }

    /**
     * @test
     */
    public function it_specifies_operator_as_less_than()
    {
        $query = $this->queryBuilder->parse('age', '<', 20);

        $this->assertArrayHasKey('age', $query);
        $this->assertArrayHasKey('$lt', $query['age']);
        $this->assertEquals(20, $query['age']['$lt']);
    }

    /**
     * @test
     */
    public function it_specifies_operator_as_less_than_equals()
    {
        $query = $this->queryBuilder->parse('age', '<=', 20);

        $this->assertArrayHasKey('age', $query);
        $this->assertArrayHasKey('$lte', $query['age']);
        $this->assertEquals(20, $query['age']['$lte']);
    }

    /**
     * @test
     * @expectedException \Packedge\Mongorm\Query\InvalidOperatorException
     */
    public function it_specifies_invalid_operator()
    {
        $this->queryBuilder->parse('first', '...', 'Alfred');
    }
}
