<?php

namespace Rougin\Wildfire;

use Rougin\SparkPlug\Instance;

/**
 * Wildfire Test
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WildfireTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \CI_Controller
     */
    protected $ci;

    /**
     * @var integer
     */
    protected $expected = 10;

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
        $path = (string) __DIR__ . '/TestApp';

        $this->ci = Instance::create($path);

        $this->ci->load->database();

        $this->ci->load->model($this->table);

        $this->ci->load->model('user');
    }

    /**
     * Tests Wildfire::get method.
     *
     * @return void
     */
    public function testGetMethod()
    {
        $wildfire = new Wildfire($this->ci->db);

        $result = $wildfire->get($this->table)->result();

        $this->assertCount($this->expected, $result);
    }

    /**
     * Tests Wildfire::get method with a different table name.
     *
     * @return void
     */
    public function testGetMethodWithDifferentTableName()
    {
        $this->ci->load->model('comment');

        $wildfire = new Wildfire($this->ci->db);

        $result = $wildfire->get('comment')->result();

        $this->assertCount($this->expected, $result);
    }

    /**
     * Tests the library using a query.
     *
     * @return void
     */
    public function testQueryMethod()
    {
        $query = (string) 'SELECT * FROM ' . $this->table;

        $query = $this->ci->db->query($query);

        $wildfire = new Wildfire($this->ci->db, $query);

        $result = $wildfire->result();

        $this->assertCount($this->expected, $result);
    }

    /**
     * Tests Wildfire::as_dropdown method.
     *
     * @return void
     */
    public function testAsDropdownMethod()
    {
        $wildfire = new Wildfire($this->ci->db);

        $result = $wildfire->get($this->table)->as_dropdown();

        $this->assertCount($this->expected, $result);
    }

    /**
     * Tests Wildfire::as_dropdown method.
     *
     * @return void
     */
    public function testFindMethod()
    {
        $wildfire = new Wildfire($this->ci->db);

        $expected = (integer) 1;

        $post = $wildfire->find($this->table, $expected);

        $result = $post->get_id();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Wildfire::as_dropdown method with an error.
     *
     * @return void
     */
    public function testFindMethodError()
    {
        $wildfire = new Wildfire($this->ci->db);

        $post = $wildfire->find($this->table, 11);

        $this->assertEmpty($post);
    }

    /**
     * Tests Wildfire without $this->db.
     *
     * @return void
     */
    public function testWildfireWithoutConstructor()
    {
        $this->ci->db->limit($expected = 5);

        $wildfire = new Wildfire;

        $wildfire->set_database($this->ci->db);

        $data = $wildfire->get($this->table);

        $result = $data->result();

        $this->assertCount($expected, $result);
    }

    /**
     * Tests Wildfire::set_query.
     *
     * @return void
     */
    public function testSetQueryMethod()
    {
        $query = 'SELECT * FROM ' . $this->table;

        $query = $this->ci->db->query($query);

        $wildfire = new Wildfire($this->ci->db);

        $wildfire->set_query($query);

        $result = $wildfire->result();

        $this->assertCount($this->expected, $result);
    }
}
