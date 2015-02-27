<?php namespace Model;

use Packedge\Mongorm\Eloquent\Model;

class Cat extends Model
{
    public function getAgeAttribute($value)
    {
        return $value / 10;
    }
}

class AttributeGettingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Cat
     */
    protected $model;

    public function setUp()
    {
        $this->model = new Cat;
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
        $this->model->setAttribute('legs', 4);
        $this->assertSame(4, $this->model->legs);
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

    /** @test */
    public function it_detects_if_it_has_a_get_mutator()
    {
        $this->assertTrue($this->model->hasGetMutator('age'));
        $this->assertFalse($this->model->hasGetMutator('name'));
    }

    /** @test */
    public function it_can_mutate_a_value_when_being_gotten()
    {
        $this->model->age = 30;
        $this->assertSame(3, $this->model->age);
    }
}
