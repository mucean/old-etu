<?php
namespace Etu\Mvc;

class View {
    /**
     * application root path
     *
     * @var string
     **/
    protected $root = null;
    protected function __construct() {}

    public static function getInstance($data, $class) {
        $real_class = sprintf('View\%s', ucfirst(strtolower($class)));
        if(!is_subclass_of($real_class, get_called_class())) {
            throw \Exception(sprintf('%s type is not exists', $class));
        }
        return new $real_class;
    }
}

