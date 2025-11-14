<?php

namespace Tests;

use Maksym\Config\ConfigException;
use Maksym\Config\ConfigDriver;

class ConfigDriverTest extends _BaseTestCase
{

    /**
     * @return void
     * @throws ConfigException
     */
    public function testGetInstance()
    {
        $instance = ConfigDriver::getInstance(__DIR__ . DIRECTORY_SEPARATOR . "config/main.php");
        $this->assertInstanceOf('Maksym\Config\ConfigDriver', $instance);
    }

    /**
     * @return void
     */
    public function testGetExisted()
    {
        $val = self::$config_instance->get('test-param');
        $this->assertEquals('test-value', $val);
    }

    /**
     * @return void
     */
    public function testGetNonExisted()
    {
        $val = self::$config_instance->get('test-param-non-exist');
        $this->assertEquals(null, $val);
    }

    /**
     * @return void
     */
    public function testGetNonExistedButDefaultValue()
    {
        $val = self::$config_instance->get('test-param-non-exist', 111);
        $this->assertEquals(111, $val);
    }

    /**
     * @return void
     */
    public function testExistYes()
    {
        $val = self::$config_instance->exist('test-param');
        $this->assertTrue($val);
    }

    /**
     * @return void
     */
    public function testExistNo()
    {
        $val = self::$config_instance->exist('test-param-non-exist');
        $this->assertEquals(false, $val);
    }

    /**
     * @return void
     */
    public function testSet()
    {
        $val = date('Y-m-d H:i:s');
        $set = self::$config_instance->set('test-param-to-set', $val);
        $this->assertTrue($set);
        $this->assertEquals($val, self::$config_instance->get('test-param-to-set'));
    }
}