<?php

namespace Rougin\Wildfire;

use Rougin\SparkPlug\Instance;

/**
 * Paginate Trait Test
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class PaginateTraitTest extends \PHPUnit_Framework_TestCase
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

        $this->ci->load->model('post');
    }

    /**
     * Tests PaginateTrait::paginate.
     *
     * @return void
     */
    public function testPaginateMethod()
    {
        $expected = (integer) 10;

        $config = array('page_query_string' => true);

        $config['use_page_numbers'] = true;

        $_GET['per_page'] = (integer) 3;

        $post = new \Post(array('user_id' => 1));

        list($result) = $post->paginate(5, 20, $config);

        $this->assertEquals($expected, $result);
    }
}
