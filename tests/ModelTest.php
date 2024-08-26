<?php

namespace Rougin\Wildfire;

use Rougin\SparkPlug\Instance;

/**
 * @package Wildfire
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ModelTest extends Testcase
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
    }

    /**
     * @return void
     */
    public function test_magic_property()
    {
        $this->ci->load->model('user');

        $expected = 'Rougin Gutib';

        $data = array('name' => $expected);

        $user = new \User((array) $data);

        $result = $user->name;

        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function test_magic_property_with_parent()
    {
        $this->ci->load->model('user');

        $data = array('name' => 'Testing');

        $user = new \User((array) $data);

        $user->load->helper('inflector');

        $exists = function_exists('singular');

        $this->assertTrue($exists === true);
    }

    /**
     * @return void
     */
    public function test_model_as_string()
    {
        $this->ci->load->model('user');

        $data = array('id' => 1, 'age' => 20);

        $user = new \User((array) $data);

        $expected = '{"id":1,"age":20}';

        $result = (string) $user->__toString();

        $this->assertEquals($expected, $result);
    }
}
