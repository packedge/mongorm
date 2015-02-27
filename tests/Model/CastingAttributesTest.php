<?php namespace Model;

use Packedge\Mongorm\Eloquent\Model;

class User extends Model
{
    protected $casts = [
        'admin' => 'boolean',
        'male' => 'bool',
        'age' => 'int',
        'foot_size' => 'integer',
        'real_value' => 'real',
        'float_value' => 'float',
        'double_value' => 'double',
        'object_value' => 'object',
        'array_value' => 'array',
        'json_value' => 'json',
    ];
}

class CastingAttributesTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var User
     */
    protected $model;

    public function setUp()
    {
        $this->model = new User;
    }

    /** @test */
    public function it_casts_booleans()
    {
        $this->model->admin = 1;
        $this->assertInternalType('bool', $this->model->admin);
        $this->assertTrue($this->model->admin);

        $this->model->male = "0";
        $this->assertInternalType('bool', $this->model->male);
        $this->assertFalse($this->model->male);
    }

    /** @test */
    public function it_casts_ints()
    {
        $this->model->age = "27";
        $this->assertInternalType('int', $this->model->age);
        $this->assertEquals(27, $this->model->age);

        $this->model->foot_size = "6";
        $this->assertInternalType('int', $this->model->foot_size);
        $this->assertEquals(6, $this->model->foot_size);
    }

    /** @test */
    public function it_casts_floats()
    {
        $this->model->real_value = "27.3";
        $this->assertInternalType('float', $this->model->real_value);
        $this->assertEquals(27.3, $this->model->real_value);

        $this->model->float_value = "45.17";
        $this->assertInternalType('float', $this->model->float_value);
        $this->assertEquals(45.17, $this->model->float_value);

        $this->model->double_value = "16.7";
        $this->assertInternalType('float', $this->model->double_value);
        $this->assertEquals(16.7, $this->model->double_value);
    }

    /** @test */
    public function it_casts_objects()
    {
        $this->model->object_value = '{"name": "bob"}';
        $this->assertInternalType('object', $this->model->object_value);
        $this->assertEquals('bob', $this->model->object_value->name);
    }

    /** @test */
    public function it_casts_arrays()
    {
        $this->model->array_value = '{"name": "dave"}';
        $this->assertInternalType('array', $this->model->array_value);
        $this->assertEquals('dave', $this->model->array_value['name']);

        $this->model->json_value = '{"name": "frank"}';
        $this->assertInternalType('array', $this->model->json_value);
        $this->assertEquals('frank', $this->model->json_value['name']);
    }
}
