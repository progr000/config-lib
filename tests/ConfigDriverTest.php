<?php

namespace Tests;

use Maksym\Config\ConfigException;
use Maksym\Config\ConfigDriver;
use PHPUnit\Framework\TestCase;

class ConfigDriverTest extends TestCase
{
    protected static $config_instance;

    /**
     * @return void
     */
    public function testGetInstanceFail()
    {
        try {
            self::$config_instance = ConfigDriver::getInstance(__DIR__ . DIRECTORY_SEPARATOR . "../src/not-exist.php");
            $this->assertInstanceOf('Maksym\Config\ConfigDriver', self::$config_instance);
        } catch (\Exception $e) {
            $this->assertInstanceOf('Maksym\Config\ConfigException', $e);
            $this->assertContains("doesn't exist", $e->getMessage());
        }
    }

    /**
     * @return void
     * @throws ConfigException
     */
    public function testGetInstance()
    {
        self::$config_instance = ConfigDriver::getInstance(__DIR__ . DIRECTORY_SEPARATOR . "../src/config-example.php");
        $this->assertInstanceOf('Maksym\Config\ConfigDriver', self::$config_instance);
    }

    /**
     * @return void
     */
    public function testLoadConfigFileFailOnDir()
    {
        try {
            self::$config_instance->loadConfigFile(__DIR__ . DIRECTORY_SEPARATOR . "../src");
        } catch (\Exception $e) {
            $this->assertInstanceOf('Maksym\Config\ConfigException', $e);
            $this->assertContains("is not a file or not readable file", $e->getMessage());
        }
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
    public function testGetExistedSecondLevel()
    {
        $val = self::$config_instance->get('caching->driver');
        $this->assertEquals('file', $val);
    }

    /**
     * @return void
     */
    public function testGetNotExistedSecondLevel()
    {
        $val = self::$config_instance->get('caching->driver2');
        $this->assertNull($val);
    }

    /**
     * @return void
     */
    public function testGetNotExisted()
    {
        $val = self::$config_instance->get('test-param-non-exist');
        $this->assertEquals(null, $val);
    }

    /**
     * @return void
     */
    public function testGetNotExistedButDefaultValue()
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