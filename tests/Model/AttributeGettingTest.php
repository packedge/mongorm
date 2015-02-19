<?php namespace Model;

use Packedge\Mongorm\Model;

class Pear extends Model {}

class AttributeGettinhTest extends \PHPUnit_Framework_TestCase
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
    public function it_can_get_an_attribute_value()
    {
        $this->model->setAttribute('name', 'Dave');
        $this->assertSame('Dave', $this->model->getAttribute('name'));
    }

    /** @test */
    public function it_can_get_an_attribute_via_magic_methods()
    {
        $this->model->setAttribute('age', 22);
        $this->assertSame(22, $this->model->age);
    }

    /** @test */
    public function it_can_get_an_attribute_like_an_array()
    {
        $this->model->setAttribute('favorite_fruit', 'Pineapple');
        $this->assertSame('Pineapple', $this->model['favorite_fruit']);
    }

    /** @test */
    public function it_returns_null_for_non_existent_attribute()
    {
        $this->assertNull($this->model->getAttribute('eye_colour'));
        $this->assertNull($this->model->eye_colour);
        $this->assertNull($this->model['eye_colour']);
    }
}
 