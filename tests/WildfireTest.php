<?php

namespace Rougin\Wildfire\Test;

use Rougin\Wildfire\Wildfire;
use Rougin\SparkPlug\Instance;

use PHPUnit_Framework_TestCase;

class WildfireTest extends PHPUnit_Framework_TestCase
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
    protected $table = 'post';

    /**
     * Sets up the CodeIgniter application.
     *
     * @return void
     */
    public function setUp()
    {
        $appPath = __DIR__ . '/TestApp';

        $this->ci = Instance::create($appPath);

        $this->ci->load->database();
        $this->ci->load->model($this->table);
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
        $posts = $wildfire->get($this->table)->result();

        $this->assertCount($this->expectedRows, $posts);
    }

    /**
     * Tests the library using a query.
     * 
     * @return void
     */
    public function testQueryMethod()
    {
        $query = $this->ci->db->query('SELECT * FROM ' . $this->table);
        $wildfire = new Wildfire($this->ci->db, $query);
        $posts = $wildfire->result();

        $this->assertCount($this->expectedRows, $posts);
    }

    /**
     * Tests Wildfire::as_dropdown method.
     * 
     * @return void
     */
    public function testAsDropdownMethod()
    {
        $wildfire = new Wildfire($this->ci->db);
        $posts = $wildfire->get($this->table)->as_dropdown();

        $this->assertCount($this->expectedRows, $posts);
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
        $post = $wildfire->find($this->table, $expectedId);

        $this->assertEquals($expectedId, $post->get_id());
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
        $post = $wildfire->find($this->table, $expectedId);

        $this->assertEmpty($post);
    }

    /**
     * Tests Wildfire without $this->db.
     * 
     * @return void
     */
    public function testWildfireWithoutConstructor()
    {
        $this->ci->db->limit(5);

        $wildfire = new Wildfire;
        $wildfire->set_database($this->ci->db);
        $posts = $wildfire->get($this->table)->result();

        $this->assertCount(5, $posts);
    }

    /**
     * Tests Wildfire::set_query.
     * 
     * @return void
     */
    public function testSetQueryMethod()
    {
        $query = $this->ci->db->query('SELECT * FROM ' . $this->table);

        $wildfire = new Wildfire($this->ci->db);
        $wildfire->set_query($query);

        $posts = $wildfire->result();

        $this->assertCount($this->expectedRows, $posts);
    }
}
