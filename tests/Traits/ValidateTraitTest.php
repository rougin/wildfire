<?php

namespace Rougin\Wildfire\Traits;

use Rougin\SparkPlug\Instance;
use Rougin\Wildfire\Testcase;

/**
 * @package Wildfire
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ValidateTraitTest extends Testcase
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

        $this->ci->load->model('comment');
    }

    /**
     * @return void
     */
    public function test_validation_errors()
    {
        $expected = array('name' => 'The Name field is required.');

        $comment = new \Comment(array());

        $comment->validate(array('message' => 'test'));

        $result = (array) $comment->errors();

        $this->assertEquals($expected, $result);
    }
}
