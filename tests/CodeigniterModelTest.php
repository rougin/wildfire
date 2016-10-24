<?php

namespace Rougin\Wildfire;

class CodeigniterModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \CI_Controller
     */
    protected $ci;

    /**
     * @var integer
     */
    protected $expectedRows = 10;

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
        $appPath = __DIR__ . '/TestApp';

        $this->ci = \Rougin\SparkPlug\Instance::create($appPath);

        $this->ci->load->helper('inflector');

        $this->ci->load->model(singular($this->table), '', true);
    }

    /**
     * Tests CodeigniterModel::get method.
     *
     * @return void
     */
    public function testGetMethod()
    {
        $this->assertCount($this->expectedRows - 1, $this->ci->comment->all());
    }

    /**
     * Tests CodeigniterModel::find method.
     *
     * @return void
     */
    public function testFindMethod()
    {
        $expectedId   = 2;
        $expectedName = 'm5yq';

        $comment = $this->ci->comment->find($expectedId);

        $this->assertEquals($expectedName, $comment->name);
    }

    /**
     * Tests the hidden columns array.
     *
     * @return void
     */
    public function testHiddenColumns()
    {
        $expectedId = 2;

        $comment = $this->ci->comment->find($expectedId);

        $this->assertFalse(property_exists($comment, 'id'));
    }

    /**
     * Tests CodeigniterModel::with method.
     *
     * @return void
     */
    public function testWithMethod()
    {
        $this->ci->load->model('post', '', true);

        $comments = $this->ci->post->with('user')->all();

        $this->assertEquals(1, $comments[0]->user->id);
    }

    /**
     * Tests CodeigniterModel::delete method.
     *
     * @return void
     */
    public function testDeleteMethod()
    {
        $data = [ 'name' => 'test', 'message' => 'test' ];

        $id = $this->ci->comment->insert($data);

        $this->ci->comment->delete($id);

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
        $expectedId = 3;

        $data = [ 'name' => 'test', 'message' => 'test' ];

        $this->ci->comment->update($expectedId, $data);

        $comment = $this->ci->comment->find($expectedId);

        $this->assertEquals($data['name'], $comment->name);
    }

    /**
     * Tests CodeigniterModel::validation method.
     *
     * @return void
     */
    public function testValidateMethod()
    {
        $expected  = [ 'name' => 'The Name field is required.' ];
        $validated = $this->ci->comment->validate([ 'message' => 'test' ]);

        $this->assertEquals($expected, $this->ci->comment->validation_errors());
    }
}
