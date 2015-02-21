<?php namespace Model;

use DateTime;
use MongoCode;
use MongoDate;
use MongoId;
use MongoInt32;
use MongoInt64;
use Packedge\Mongorm\Eloquent\ConvertableTrait;

class Sample
{
    use ConvertableTrait;
}

class ConvertMongoTypesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Sample
     */
    protected $model;

    public function setUp()
    {
        $this->model = new Sample;
    }

    /** @test */
    public function it_converts_mongo_id_to_string()
    {
        $id = '123456789012345678901234';
        $result = $this->model->convertMongoType(new MongoId($id));
        $this->assertInternalType('string', $result);
        $this->assertSame($id, $result);
    }

    /** @test */
    public function it_converts_a_string_to_a_mongo_id()
    {
        $id = '123456789012345678901234';
        $result = $this->model->convertToMongoId($id);
        $this->assertInstanceOf('MongoId', $result);
        $this->assertEquals($id, (string) $result);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_throws_exception_if_non_string_id_used()
    {
        $id = 123456789012345678901234;
        $this->model->convertToMongoId($id);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_throws_exception_if_invalid_length()
    {
        $id = "123";
        $this->model->convertToMongoId($id);
    }

    /** @test */
    public function it_converts_mongo_code_to_string()
    {
        $mc = new MongoCode('function() { return this.x < 5; }');
        $result = $this->model->convertMongoType($mc);
        $this->assertInternalType('string', $result);
        $this->assertSame('function() { return this.x < 5; }', $result);

        // With substitution vars set
        $mc = new MongoCode('function() { return this.x < y; }', ['y' => 7]);
        $result = $this->model->convertMongoType($mc);
        $this->assertInternalType('string', $result);
        $this->assertSame('function() { return this.x < y; }', $result);
    }

    /** @test */
    public function it_converts_mongo_date_to_php_date()
    {
        $epoc = strtotime("2015-02-21 00:00:00");
        $date = new MongoDate($epoc);

        $result = $this->model->convertMongoDate($date);
        $this->assertEquals(new DateTime($epoc), $result);
    }

    /** @test */
    public function it_converts_mongo_regex_into_a_string()
    {
        $regex = "/^a/i";
        $reg = new \MongoRegex($regex);

        $result = $this->model->convertMongoRegex($reg);
        $this->assertEquals($regex, $result);
    }

    /** @test */
    public function it_converts_mongo_int_32_into_an_int()
    {
        $value = new MongoInt32('123');

        $result = $this->model->convertMongoInt32($value);
        $this->assertInternalType('int', $result);
        $this->assertEquals(123, $result);
    }

    /** @test */
    public function it_converts_mongo_int_64_into_an_int()
    {
        $value = new MongoInt64('123');

        $result = $this->model->convertMongoInt64($value);
        $this->assertInternalType('int', $result);
        $this->assertEquals(123, $result);
    }
}
 