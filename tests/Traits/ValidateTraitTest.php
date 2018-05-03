<?php

namespace Rougin\Wildfire;

use Rougin\SparkPlug\Instance;

/**
 * Validate Trait Test
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ValidateTraitTest extends \PHPUnit_Framework_TestCase
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

        $this->ci->load->model('comment');
    }

    /**
     * Tests ValidateTrait::validate.
     *
     * @return void
     */
    public function testValidateMethod()
    {
        $expected = array('name' => 'The Name field is required.');

        $comment = new \Comment(array());

        $comment->validate(array('message' => 'test'));

        $result = (array) $comment->errors();

        $this->assertEquals($expected, $result);
    }
}
