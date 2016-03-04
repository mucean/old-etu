<?php
namespace Etu;

class Config {
    protected static $configs = [];

    public static function merge($path) {
        if(!file_exists($path)) {
            throw new \Exception('config file is not exist');
        }

        $required_config = require($path);
        if(!is_array($required_config)) {
            throw new \Exception('config form is not wright');
        }
        self::$configs = array_merge(self::configs, $required_config);
    }

    public static function get($name = null) {
        if(empty($name)) {
            return self::$configs;
        }

        if(!isset(self::$configs[$name])) {
            throw new \Exception('this config is not exist');
        }

        return self::configs[$name];
    }
}
