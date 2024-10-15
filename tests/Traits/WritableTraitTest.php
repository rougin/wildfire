<?php

namespace Rougin\Wildfire\Traits;

use Rougin\SparkPlug\Instance;
use Rougin\Wildfire\Testcase;

/**
 * @package Wildfire
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WritableTraitTest extends Testcase
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

        $this->ci->load->model('item');
    }

    /**
     * @return void
     */
    public function test_create_method()
    {
        $data = array('name' => 'Wildfire');

        $expected = $data['name'];

        $this->ci->item->create($data);

        /** @var \Item */
        $model = $this->ci->item->find(1);

        $actual = $model->name;

        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends test_update_method
     *
     * @return void
     */
    public function test_delete_method()
    {
        $result = $this->ci->item->delete(1);

        $this->assertTrue($result);
    }

    /**
     * @depends test_create_method
     *
     * @return void
     */
    public function test_update_method()
    {
        $data = array('name' => 'Weasley');

        $expected = $data['name'];

        $this->ci->item->update(1, $data);

        /** @var \Item */
        $model = $this->ci->item->find(1);

        $actual = $model->name;

        $this->assertEquals($expected, $actual);
    }
}
