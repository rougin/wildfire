<?php

namespace Rougin\Wildfire\Test;

use Rougin\Wildfire\Inflector;

use PHPUnit_Framework_TestCase;

class InflectorTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests Inflector::singular method.
     * 
     * @return void
     */
    public function testSingularMethod()
    {
        $expected = 'user';
        $singular = Inflector::singular('users');

        $this->assertEquals($expected, $singular);
    }

    /**
     * Tests Inflector::singular method with plural.
     * 
     * @return void
     */
    public function testSingularMethodWithPlural()
    {
        $expected = 'equipment';
        $singular = Inflector::singular('equipment');

        $this->assertEquals($expected, $singular);
    }
}
