<?php namespace Model;

use Packedge\Mongorm\Model;

class Pineapple extends Model {}

class ToArrayJsonTest extends \PHPUnit_Framework_TestCase
{
    protected $model;

    public function setUp()
    {
        $this->model = new Pineapple;
    }
    
    /*
     * Handle dates
     * handle casts
     * handle hidden fields
     * handle appended fields
     *
     * handle relationships when needed
     */
}
 