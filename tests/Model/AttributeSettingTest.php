<?php namespace Model;

use Packedge\Mongorm\Eloquent\Model;

class Dog extends Model
{
    public function setAgeAttribute($value)
    {
        $this->attributes['age'] = $value * 7;
    }
}

class AttributeSettingTest extends \TestCase
{
    /**
     * @var Dog
     */
    protected $model;

    public function setUp()
    {
        $this->model = new Dog;
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
        $this->model->legs = 4;
        $this->assertArrayHasKey('legs', $this->model->getAttributes());
        $this->assertSame(4, $this->model->getAttributes()['legs']);
    }

    /** @test */
    public function it_can_set_an_attribute_like_an_array()
    {
        $this->model['gender'] = 'Male';
        $this->assertArrayHasKey('gender', $this->model->getAttributes());
        $this->assertSame('Male', $this->model->getAttributes()['gender']);
    }

    /** @test */
    public function it_detects_if_it_has_a_set_mutator()
    {
        $this->assertTrue($this->model->hasSetMutator('age'));
        $this->assertFalse($this->model->hasSetMutator('name'));
    }

    /** @test */
    public function it_can_mutate_a_value_when_being_set()
    {
        $this->model->age = 3;
        $this->assertSame(21, $this->model->getAttributes()['age']);
    }
}
