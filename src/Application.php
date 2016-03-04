<?php
namespace Etu;
/**
 * Application
 *
 * @package default
 * @author MoceanLiu
**/
class Application 
{
    /**
     * autoloadRegister
     * register a function to autoload class
     * @return void
     **/
    public static function autoloadRegister($namespace, $path) 
    {
        $namespace = self::addFirstChar($namespace, '\\');
        $path = rtrim($path, '/\\');
        $default_method = function ($class) use ($namespace, $path) {
            $class = self::addFirstChar($class, '\\');
            $pos = strpos($class, $namespace);
            if ($pos === false) {
                return false;
            }
            $filename = trim(substr($class, $pos + strlen($namespace)));
            $filename = $path.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $filename).'.php';
            if (!file_exists($filename)) {
                return false;
            }
            require_once($filename);
            return class_exists($class, false) || interface_exists($class, false);
        };

        spl_autoload_register($default_method);
    }
    /**
     * 
     *
     * @return void
     **/
    public static function addFirstChar($string, $char) {
        $string = (string)$string;
        $char = (string)$char;
        if (empty($string) || $string[0] !== $char) {
            return $char.$string;
        }
        return $string;
    }
}
