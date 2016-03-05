<?php

namespace Rougin\Wildfire\Test;

use Rougin\Wildfire\Wildfire;
use Rougin\SparkPlug\SparkPlug;

use PHPUnit_Framework_TestCase;

class WildfireTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \CI_Controller
     */
    protected $ci;

    /**
     * Sets up the CodeIgniter application.
     *
     * @return void
     */
    public function setUp()
    {
        $appPath = __DIR__ . '/TestApp';

        $sparkPlug = new SparkPlug($GLOBALS, $_SERVER, $appPath);
        $this->ci = $sparkPlug->getCodeIgniter();

        $this->ci->load->database();
        $this->ci->load->model('user');
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
        $wildfire = new Wildfire($this->ci->db);
        $users = $wildfire->get('users')->result();

        $this->assertEquals(10, count($users));
    }

    /**
     * Tests the library using a query.
     * 
     * @return void
     */
    public function testQueryMethod()
    {
        $query = $this->ci->db->query('SELECT * FROM users');
        $wildfire = new Wildfire($this->ci->db, $query);
        $users = $wildfire->result();

        $this->assertEquals(10, count($users));
    }

    /**
     * Tests Wildfire::as_dropdown method.
     * 
     * @return void
     */
    public function testAsDropdownMethod()
    {
        $wildfire = new Wildfire($this->ci->db);
        $users = $wildfire->get('users')->as_dropdown('name');

        $this->assertEquals(10, count($users));
    }

    /**
     * Tests Wildfire::as_dropdown method.
     * 
     * @return void
     */
    public function testFindMethod()
    {
        $expectedId = 1;
        $wildfire = new Wildfire($this->ci->db);
        $user = $wildfire->find('users', $expectedId);

        $this->assertEquals($expectedId, $user->id);
    }

    /**
     * Tests Wildfire::as_dropdown method with an error.
     * 
     * @return void
     */
    public function testFindMethodError()
    {
        $expectedId = 11;
        $wildfire = new Wildfire($this->ci->db);
        $user = $wildfire->find('users', $expectedId);

        $this->assertEmpty($user);
    }
}
