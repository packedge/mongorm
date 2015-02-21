<?php namespace Model;

use Carbon\Carbon;
use Packedge\Mongorm\Eloquent\Model;

class Watch extends Model
{
    protected $dates = ['data'];
}

class ConvertDatesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Watch
     */
    protected $model;

    public function setUp()
    {
        $this->model = new Watch;
    }

    /** @test */
    public function it_includes_created_at_and_updated_at_as_dates()
    {
        $dates = $this->model->getDates();
        $this->assertContains('data', $dates);
        $this->assertContains('updated_at', $dates);
        $this->assertContains('created_at', $dates);
    }

    /** @test */
    public function it_converts_time_stamps()
    {
        $timestamp = 1451046521;
        $this->model->data = $timestamp;
        $dt = Carbon::createFromTimestamp($timestamp);

        $this->assertInstanceOf('Carbon\Carbon', $this->model->data);
        $this->assertEquals($dt, $this->model->data);
    }

    /** @test */
    public function it_converts_from_y_m_d_format()
    {
        $strDate = '2015-08-24';
        $this->model->data = $strDate;
        $date = Carbon::createFromFormat('Y-m-d', $strDate)->startOfDay();

        $this->assertInstanceOf('Carbon\Carbon', $this->model->data);
        $this->assertEquals($date, $this->model->data);
    }
}
 