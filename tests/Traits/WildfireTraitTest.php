<?php

namespace Rougin\Wildfire;

use Rougin\SparkPlug\Instance;
use Rougin\Wildfire\Wildfire;

/**
 * Wildfire Trait Test
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WildfireTraitTest extends \PHPUnit_Framework_TestCase
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

        $this->ci->load->database();

        $this->ci->load->model('user');
    }

    /**
     * Tests WildfireTrait::__call.
     *
     * @return void
     */
    public function testCallMagicMethod()
    {
        $wildfire = new Wildfire($this->ci->db);

        $this->ci->user->wildfire($wildfire);

        $this->ci->user->where('name', 'Royce');

        $wildfire = $this->ci->user->get('users');

        $data = array('id' => 2, 'name' => 'Royce');

        $data['age'] = (integer) 18;

        $data['gender'] = 'male';

        $data['accepted'] = 0;

        $expected = new \User($data);

        $result = current($wildfire->result());

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests WildfireTrait::find.
     *
     * @return void
     */
    public function testFindMethod()
    {
        $data = array('id' => 3, 'name' => 'Angel');

        $data['age'] = (integer) 19;

        $data['gender'] = 'female';

        $data['accepted'] = 0;

        $expected = new \User((array) $data);

        $wildfire = new Wildfire($this->ci->db);

        $this->ci->user->wildfire($wildfire);

        $result = $this->ci->user->find($data['id']);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests WildfireTrait::get.
     *
     * @return void
     */
    public function testGetMethod()
    {
        $expected = array();

        $item = array('id' => '1');
        $item['name'] = 'Rougin';
        $item['age'] = '20';
        $item['gender'] = 'male';
        $item['accepted'] = '0';

        $expected[] = new \User($item);

        $item = array('id' => '2');
        $item['name'] = 'Royce';
        $item['age'] = '18';
        $item['gender'] = 'male';
        $item['accepted'] = '0';

        $expected[] = new \User($item);

        $item = array('id' => '3');
        $item['name'] = 'Angel';
        $item['age'] = '19';
        $item['gender'] = 'female';
        $item['accepted'] = '0';

        $expected[] = new \User($item);

        $wildfire = new Wildfire($this->ci->db);

        $this->ci->user->wildfire($wildfire);

        $result = $this->ci->user->get()->result();

        $this->assertEquals($expected, $result);
    }
}
