<?php

namespace Rougin\Wildfire;

use Rougin\SparkPlug\Instance;

/**
 * Codeigniter Model Test
 *
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class CodeigniterModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \CI_Controller
     */
    protected $ci;

    /**
     * @var integer
     */
    protected $expected = 10;

    /**
     * @var string
     */
    protected $table = 'comments';

    /**
     * Sets up the CodeIgniter application.
     *
     * @return void
     */
    public function setUp()
    {
        $path = (string) __DIR__ . '/TestApp';

        $this->ci = Instance::create($path);

        $this->ci->load->helper('inflector');

        $table = (string) singular($this->table);

        $this->ci->load->model($table, '', true);
    }

    /**
     * Tests CodeigniterModel::get method.
     *
     * @return void
     */
    public function testGetMethod()
    {
        $expected = (integer) $this->expected - 1;

        $result = $this->ci->comment->all();

        $this->assertCount($expected, $result);
    }

    /**
     * Tests CodeigniterModel::find method.
     *
     * @return void
     */
    public function testFindMethod()
    {
        $expected = (string) 'm5yq';

        $comment = $this->ci->comment->find(2);

        $result = (string) $comment->name;

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the hidden columns array.
     *
     * @return void
     */
    public function testHiddenColumns()
    {
        $comment = $this->ci->comment->find(2);

        $exists = property_exists($comment, 'id');

        $this->assertFalse($exists);
    }

    /**
     * Tests CodeigniterModel::with method.
     *
     * @return void
     */
    public function testWithMethod()
    {
        $this->ci->load->model('post', '', true);

        $expected = (integer) 1;

        $comments = $this->ci->post->with('user')->all();

        $result = $comments[0]->user->id;

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests CodeigniterModel::delete method.
     *
     * @return void
     */
    public function testDeleteMethod()
    {
        $data = array('name' => 'test', 'message' => 'test');

        $id = $this->ci->comment->insert($data);

        $this->ci->comment->delete((integer) $id);

        $comment = $this->ci->comment->find($id);

        $this->assertTrue(empty($comment));
    }

    /**
     * Tests CodeigniterModel::update method.
     *
     * @return void
     */
    public function testUpdateMethod()
    {
        $data = array('name' => 'test', 'message' => 'test');

        $this->ci->comment->update(3, $data);

        $expected = $data['name'];

        $comment = $this->ci->comment->find(3);

        $result = $comment->name;

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests CodeigniterModel::validation method.
     *
     * @return void
     */
    public function testValidateMethod()
    {
        $expected = array('name' => 'The Name field is required.');

        $data = array('message' => 'test');

        $validated = $this->ci->comment->validate($data);

        $result = $this->ci->comment->validation_errors();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests CodeigniterModel::paginate method.
     *
     * @return void
     */
    public function testPaginateMethod()
    {
        $config = array('page_query_string' => true);

        $config['use_page_numbers'] = true;

        $_GET['per_page'] = 1;

        $expected = (integer) 5;

        $item = $this->ci->post->paginate($expected, $config);

        list($result, $links) = $item;

        $this->assertCount($expected, $result);
    }
}
