<?php namespace Model;

use Packedge\Mongorm\Eloquent\Model;

class Kiwi extends Model
{
}
class Banana extends Model
{
    protected $primaryKey = 'identifier';
}

class PrimaryKeyTest extends \TestCase
{
    /**
     * @var Model
     */
    protected $model;

    public function setUp()
    {
        $this->model = new Kiwi;
    }

    /** @test */
    public function it_uses_underscore_id_as_the_default_value()
    {
        $this->assertSame('_id', $this->model->getKeyName());
    }

    /** @test */
    public function it_can_override_the_value_at_any_time()
    {
        $this->assertSame('_id', $this->model->getKeyName());

        $this->model->setKeyName('something');
        $this->assertSame('something', $this->model->getKeyName());
    }

    /** @test */
    public function it_can_override_the_default_by_setting_the_property()
    {
        $model = new Banana;
        $this->assertSame('identifier', $model->getKeyName());
    }
}
