<?php

namespace Maksym\Config;

use stdClass;

class ConfigDriver
{
    /** @var self */
    private static $instance;
    /** @var stdClass */
    private $container;
    /** @var array */
    private $loaded_storage = array();

    /**
     * @param string|null $config_file
     * @return ConfigDriver
     * @throws ConfigException
     */
    public static function getInstance($config_file = null)
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        if (!is_null($config_file)) {
            self::$instance->loadConfigFile($config_file);
        }

        return self::$instance;
    }

    /**
     *
     */
    private function __construct()
    {
        $this->container = new stdClass();
    }

    /**
     * @param string $config_file
     * @return void
     * @throws ConfigException
     */
    public function loadConfigFile($config_file)
    {
        $index_for_config_file = md5($config_file);
        if (!isset($this->loaded_storage[$index_for_config_file])) {

            if (!file_exists($config_file)) {
                throw new ConfigException("Configuration file '{$config_file}' doesn't exist", 500);
            }

            if (!is_file($config_file) || !is_readable($config_file)) {
                throw new ConfigException("Configuration file '{$config_file}' is not a file or not readable file", 500);
            }

            $config = require($config_file);
            foreach ($config as $k => $v) {
                $this->container->$k = $v;
            }
            $this->loaded_storage[$index_for_config_file] = $config_file;
        }
    }

    /**
     * Return value for param name
     * @param string $param
     * @param mixed $default
     * @return mixed
     */
    public function get($param, $default = null)
    {
        $test = explode('->', $param);
        if (isset($test[1])) {
            $key = $test[1];
            $param = $test[0];
        }
        if (!property_exists($this->container, $param)) {
            return $default;
        }
        if (isset($key)) {
            return isset($this->container->{$param}[$key])
                ? $this->container->{$param}[$key]
                : $default;
        } else {
            return $this->container->$param;
        }
    }

    /**
     * Check exist or not param in config
     * @param string $param
     * @return bool
     */
    public function exist($param)
    {
        return property_exists($this->container, $param);
    }

    /**
     * Set some params into config container
     * @param string|array $param
     * @param mixed|null $value
     * @return true
     */
    public function set($param, $value = null)
    {
        if (gettype($param) === 'array') {
            foreach ($param as $k => $v) {
                $this->container->$k = $v;
            }
        } else {
            $this->container->$param = $value;
        }
        return true;
    }
}