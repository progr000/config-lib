<?php
if (!function_exists('config')) {
    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     * @throws Maksym\Config\ConfigException
     */
    function config($key = null, $default = null)
    {
        $config = Maksym\Config\ConfigDriver::getInstance("");

        if (is_null($key)) {
            return $config;
        }

        if ($config && is_object($config) && method_exists($config, 'get')) {
            return $config->get($key, $default);
        }

        return $default;
    }
}
