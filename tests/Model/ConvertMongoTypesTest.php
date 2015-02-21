<?php namespace Model;

use DateTime;
use MongoCode;
use MongoDate;
use MongoId;
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
}
 