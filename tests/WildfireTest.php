<?php

namespace Rougin\Wildfire;

use Rougin\SparkPlug\Instance;

/**
 * Wildfire Test
 *
 * @package Wildfire
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class WildfireTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \CI_Controller
     */
    protected $ci;

    /**
     * Sets up the Codeigniter application.
     *
     * @return void
     */
    public function setUp()
    {
        $path = (string) __DIR__ . '/Weblog';

        $this->ci = Instance::create($path);

        $this->ci->load->helper('inflector');

        $this->ci->load->database();

        $this->ci->load->model('user');
    }

    /**
     * Tests Wildfire::__call.
     *
     * @return void
     */
    public function testCallMagicMethod()
    {
        $wildfire = new Wildfire($this->ci->db);

        $wildfire->where('name', 'Royce');

        $wildfire = $wildfire->get('users');

        $data = array('id' => 2, 'name' => 'Royce');

        $data['age'] = (integer) 18;

        $data['gender'] = 'male';

        $data['accepted'] = 0;

        $expected = new \User($data);

        $result = current($wildfire->result());

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Wildfire::__call with an exception.
     *
     * @return void
     */
    public function testCallMagicMethodWithException()
    {
        $exception = 'BadMethodCallException';

        $this->setExpectedException($exception);

        (new Wildfire($this->ci->db))->test();
    }

    /**
     * Tests Wildfire::__construct with \CI_DB_query_builder.
     *
     * @return void
     */
    public function testConstructMethodWithBuilder()
    {
        $wildfire = new Wildfire($this->ci->db);

        $wildfire = $wildfire->get('users');

        $data = array('id' => 1, 'name' => 'Rougin');

        $data['age'] = (integer) 20;

        $data['gender'] = 'male';

        $data['accepted'] = 0;

        $expected = new \User((array) $data);

        $result = current($wildfire->result());

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Wildfire::__construct with \CI_DB_result.
     *
     * @return void
     */
    public function testConstructMethodWithResult()
    {
        $query = 'SELECT name FROM users';

        $query = $this->ci->db->query($query);

        $wildfire = new Wildfire($query);

        $items = (array) $wildfire->result('User');

        $data = array('name' => 'Rougin');

        $expected = new \User((array) $data);

        $result = $items[0];

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Wildfire::dropdown.
     *
     * @return void
     */
    public function testDropdownMethod()
    {
        $wildfire = new Wildfire($this->ci->db);

        $expected = array(1 => 'Rougin');

        $expected[2] = 'Royce';

        $expected[3] = 'Angel';

        $wildfire = $wildfire->get('users');

        $result = $wildfire->dropdown('name');

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Wildfire::find.
     *
     * @return void
     */
    public function testFindMethod()
    {
        $wildfire = new Wildfire($this->ci->db);

        $data = array('id' => 3, 'name' => 'Angel');

        $data['age'] = (integer) 19;

        $data['gender'] = 'female';

        $data['accepted'] = 0;

        $expected = new \User($data);

        $result = $wildfire->find('users', $data['id']);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Wildfire::data.
     *
     * @return void
     */
    public function testDataMethod()
    {
        $expected = array();

        $item = array('name' => 'Rougin');
        $item['age'] = 20;
        $item['gender'] = 'male';
        $item['id'] = 1;
        $item['accepted'] = false;

        $expected[] = $item;

        $item = array('name' => 'Royce');
        $item['age'] = 18;
        $item['gender'] = 'male';
        $item['id'] = 2;
        $item['accepted'] = false;

        $expected[] = $item;

        $item = array('name' => 'Angel');
        $item['age'] = 19;
        $item['gender'] = 'female';
        $item['id'] = 3;
        $item['accepted'] = false;

        $expected[] = $item;

        $wildfire = new Wildfire($this->ci->db);

        $result = $wildfire->get('users')->data();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Wildfire::json.
     *
     * @return void
     */
    public function testJsonMethod()
    {
        $expected = array();

        $item = array('id' => 1);
        $item['name'] = 'Rougin';
        $item['age'] = 20;
        $item['gender'] = 'male';
        $item['accepted'] = false;

        $expected[] = $item;

        $item = array('id' => 2);
        $item['name'] = 'Royce';
        $item['age'] = 18;
        $item['gender'] = 'male';
        $item['accepted'] = false;

        $expected[] = $item;

        $item = array('id' => 3);
        $item['name'] = 'Angel';
        $item['age'] = 19;
        $item['gender'] = 'female';
        $item['accepted'] = false;

        $expected[] = $item;

        $expected = json_encode($expected);

        $wildfire = new Wildfire($this->ci->db);

        $result = $wildfire->get('users')->json();

        $this->assertEquals($expected, $result);
    }
}
