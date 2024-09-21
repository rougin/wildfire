<?php

namespace Rougin\Wildfire\Traits;

use Rougin\SparkPlug\Instance;
use Rougin\Wildfire\Testcase;
use Rougin\Wildfire\Wildfire;

/**
 * @package Wildfire
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WildfireTraitTest extends Testcase
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

        $this->ci->load->database();

        $this->ci->load->model('user');
    }

    /**
     * @return void
     */
    public function test_as_wildfire_undefined()
    {
        $data = array('id' => 3, 'name' => 'Angel');

        $data['age'] = (int) 19;

        $data['gender'] = 'female';

        $data['accepted'] = 0;

        $expected = new \User((array) $data);

        $result = $this->ci->user->find($data['id']);

        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function test_find_method_from_model()
    {
        $data = array('id' => 3, 'name' => 'Angel');

        $data['age'] = (int) 19;

        $data['gender'] = 'female';

        $data['accepted'] = 0;

        $expected = new \User((array) $data);

        $wildfire = new Wildfire($this->ci->db);

        $this->ci->user->wildfire($wildfire);

        $result = $this->ci->user->find($data['id']);

        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function test_get_method_from_model()
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

    /**
     * @return void
     */
    public function test_magic_method_from_model()
    {
        // TODO: Add methods from query builder to Wildfire as @method ---
        $this->ci->user->where('name', 'Royce');
        // ---------------------------------------------------------------

        $wildfire = $this->ci->user->get();

        $data = array('id' => 2, 'name' => 'Royce');

        $data['age'] = (int) 18;

        $data['gender'] = 'male';

        $data['accepted'] = 0;

        $expected = new \User($data);

        $result = current($wildfire->result());

        $this->assertEquals($expected, $result);
    }
}
