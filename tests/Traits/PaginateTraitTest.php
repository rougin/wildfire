<?php

namespace Rougin\Wildfire\Traits;

use Rougin\SparkPlug\Instance;
use Rougin\Wildfire\Testcase;

/**
 * @package Wildfire
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PaginateTraitTest extends Testcase
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

        $this->ci->load->model('post');
    }

    /**
     * @return void
     */
    public function test_pagination_result()
    {
        $expected = (int) 10;

        $config = array('page_query_string' => true);

        $config['use_page_numbers'] = true;

        $_GET['per_page'] = (int) 3;

        $post = new \Post(array('user_id' => 1));

        list($result) = $post->paginate(5, 20, $config);

        $this->assertEquals($expected, $result);
    }
}
