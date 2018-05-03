<?php

namespace Rougin\Wildfire;

use Rougin\SparkPlug\Instance;

/**
 * Model Test
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ModelTest extends \PHPUnit_Framework_TestCase
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
    }

    /**
     * Tests Model::__get.
     *
     * @return void
     */
    public function testGetMagicMethod()
    {
        $this->ci->load->model('user');

        $expected = 'Rougin Royce Gutib';

        $data = array('name' => $expected);

        $user = new \User((array) $data);

        $result = $user->name;

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Model::__get with its parent method.
     *
     * @return void
     */
    public function testGetMagicMethodWithParent()
    {
        $this->ci->load->model('user');

        $data = array('name' => 'Testing');

        $user = new \User((array) $data);

        $user->load->helper('inflector');

        $exists = function_exists('singular');

        $this->assertTrue($exists === true);
    }

    /**
     * Tests Model::__toString.
     *
     * @return void
     */
    public function testToStringMagicMethod()
    {
        $this->ci->load->model('user');

        $data = array('id' => 1, 'age' => 20);

        $user = new \User((array) $data);

        $expected = '{"id":1,"age":20}';

        $result = (string) $user->__toString();

        $this->assertEquals($expected, $result);
    }
}
