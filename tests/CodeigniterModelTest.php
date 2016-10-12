<?php

namespace Rougin\Wildfire;

class CodeigniterModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \CI_Controller
     */
    protected $ci;

    /**
     * @var integer
     */
    protected $expectedRows = 10;

    /**
     * @var string
     */
    protected $table = 'comment';

    /**
     * Sets up the CodeIgniter application.
     *
     * @return void
     */
    public function setUp()
    {
        $appPath = __DIR__ . '/TestApp';

        $this->ci = \Rougin\SparkPlug\Instance::create($appPath);

        $this->ci->load->database();
        $this->ci->load->model($this->table);
    }

    /**
     * Checks if the CodeIgniter instance is successfully retrieved.
     *
     * @return void
     */
    public function testCodeIgniterInstance()
    {
        $this->assertInstanceOf('CI_Controller', $this->ci);
    }

    /**
     * Tests Wildfire::get method.
     *
     * @return void
     */
    public function testGetMethod()
    {
        $this->assertCount($this->expectedRows, $this->ci->comment->all());
    }

    /**
     * Tests Wildfire::find method.
     *
     * @return void
     */
    public function testFindMethod()
    {
        $expectedId = 1;

        $comment = $this->ci->comment->find($expectedId);

        $this->assertEquals($expectedId, $comment->id);
    }
}
