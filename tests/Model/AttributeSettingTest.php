<?php namespace Model;

use Packedge\Mongorm\Model;

class Kiwi extends Model {}

class AttributeSettingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Kiwi
     */
    protected $model;

    public function setUp()
    {
        $this->model = new Kiwi;
    }

    /** @test */
    public function it_can_set_an_attribute_value()
    {
        $this->model->setAttribute('name', 'John');
        $this->assertArrayHasKey('name', $this->model->getAttributes());
        $this->assertSame('John', $this->model->getAttributes()['name']);
    }

    /** @test */
    public function it_can_set_an_attribute_value_via_magic_methods()
    {
        $this->model->age = 27;
        $this->assertArrayHasKey('age', $this->model->getAttributes());
        $this->assertSame(27, $this->model->getAttributes()['age']);
    }

    /** @test */
    public function it_can_set_an_attribute_like_an_array()
    {
        $this->model['gender'] = 'Male';
        $this->assertArrayHasKey('gender', $this->model->getAttributes());
        $this->assertSame('Male', $this->model->getAttributes()['gender']);
    }
}
 