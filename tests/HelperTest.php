<?php

namespace Tests;

use Maksym\Config\ConfigException;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    /**
     * @return void
     * @throws ConfigException
     */
    public function testConfig()
    {
        // check receive config object with null key
        $conf = config();

        $this->assertInstanceOf('Maksym\Config\ConfigDriver', $conf);
        $this->assertTrue(method_exists($conf, 'get') && method_exists($conf, 'exist'));
        $this->assertTrue($conf->exist('test-param'));
        $this->assertEquals('test-value', $conf->get('test-param'));

        // check receive config var with key
        $this->assertEquals('test-value', config('test-param'));
        $this->assertEquals(111, config('test-param-not-exist', 111));
    }

}