<?php
namespace Etu\Traits;

trait Singleton {
    protected static $__instances__ = [];

    public function __construct() {}

    public static function getInstance() {
        $class_name = get_called_class();

        if (isset(static::$__instances__[$class_name])) {
            return static::$__instances__[$class_name];
        }

        return static::$__instances__[$class_name] = new static;
    }

    public function __clone() {
        throw new \Exception('Clone Class ' . __CLASS__ . 'is not allowed!');
    }
}
