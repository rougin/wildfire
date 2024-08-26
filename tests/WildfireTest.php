<?php

namespace Rougin\Wildfire;

use Rougin\SparkPlug\Instance;

/**
 * @package Wildfire
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WildfireTest extends Testcase
{
    /**
     * @var \CI_Controller
     */
    protected $ci;

    /**
     * @return void
     */
    public function doSetUp()
    {
        $path = (string) __DIR__ . '/Weblog';

        $this->ci = Instance::create($path);

        $this->ci->load->helper('inflector');

        $this->ci->load->database();

        $this->ci->load->model('user');
    }

    /**
     * @return void
     */
    public function test_call_magic_method()
    {
        $wildfire = new Wildfire($this->ci->db);

        $wildfire->where('name', 'Royce');

        $wildfire = $wildfire->get('users');

        $data = array('id' => 2, 'name' => 'Royce');

        $data['age'] = (int) 18;

        $data['gender'] = 'male';

        $data['accepted'] = 0;

        $expected = new \User($data);

        $result = current($wildfire->result());

        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function test_call_magic_method_with_exception()
    {
        $exception = 'BadMethodCallException';

        $this->setExpectedException($exception);

        (new Wildfire($this->ci->db))->test();
    }

    /**
     * @return void
     */
    public function test_construct_with_query_builder()
    {
        $wildfire = new Wildfire($this->ci->db);

        $wildfire = $wildfire->get('users');

        $data = array('id' => 1, 'name' => 'Rougin');

        $data['age'] = (int) 20;

        $data['gender'] = 'male';

        $data['accepted'] = 0;

        $expected = new \User((array) $data);

        $result = current($wildfire->result());

        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function test_construct_with_result()
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
     * @return void
     */
    public function test_find_single_result()
    {
        $wildfire = new Wildfire($this->ci->db);

        $data = array('id' => 3, 'name' => 'Angel');

        $data['age'] = (int) 19;

        $data['gender'] = 'female';

        $data['accepted'] = 0;

        $expected = new \User($data);

        $result = $wildfire->find('users', $data['id']);

        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function test_result_as_array()
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
     * @return void
     */
    public function test_result_as_json()
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

    /**
     * @return void
     */
    public function test_results_as_dropdown()
    {
        $wildfire = new Wildfire($this->ci->db);

        $expected = array(1 => 'Rougin');

        $expected[2] = 'Royce';

        $expected[3] = 'Angel';

        $wildfire = $wildfire->get('users');

        $result = $wildfire->dropdown('name');

        $this->assertEquals($expected, $result);
    }
}
