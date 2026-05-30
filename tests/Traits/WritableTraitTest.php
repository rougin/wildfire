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

        /** @var integer */
        $insertId = $this->ci->db->insert_id();

        /** @var \Item */
        $model = $this->ci->item->find($insertId);

        $actual = $model->name;

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_update_method()
    {
        $data = array('name' => 'Wildfire');

        $this->ci->item->create($data);

        /** @var integer */
        $insertId = $this->ci->db->insert_id();

        $data = array('name' => 'Weasley');

        $expected = $data['name'];

        $this->ci->item->update($insertId, $data);

        /** @var \Item */
        $model = $this->ci->item->find($insertId);

        $actual = $model->name;

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_delete_method()
    {
        $data = array('name' => 'Wildfire');

        $this->ci->item->create($data);

        /** @var integer */
        $insertId = $this->ci->db->insert_id();

        $result = $this->ci->item->delete($insertId);

        $this->assertTrue($result);
    }
}
