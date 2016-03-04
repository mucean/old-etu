<?php
namespace Etu\MVC;

/**
 * handle uri and return the controller and parameters
 *
 * @package default
 * @author MoceanLiu
 **/
class Router {
    /**
     * rules of project
     *
     * @var array
     **/
    protected $rules = [];

    public function __construct(array $rules) {
        $this->rules = $rules;
    }

    /**
     * handle uri
     *
     * @param string $uri
     * @return array $data
     * '/aa/bb' => ['\Controller\Aa\Bb', []]
     * '/user/1' => ['\Controller\User', [1]]    // rewrite rule is: '#/user/\d+$#' => '/user'
     **/
    public function execute($uri) {
        $uri = strval($uri);
        $project = $this->matchRule($uri);

        // check the $uri if has matched class to execute
        $path = $this->rules[$project]['bath_path'].substr($uri, strlen($project));
        $path = $this->normalizeUri($path);
        $class = str_replace('/', '\\', $path);

        if(class_exists($class)) {
            return [$class, []];
        }

        if(empty($this->rules[$project]['rewrite'])) {
            // if execute to this place and have no rewrite rules will throw a exception
            throw Exception\RouterException;
        }
        foreach($this->rules[$project]['rewrite'] as $preg => $real_uri) {
            if(preg_match($preg, $uri, $notes)) {
                break;
            }
        }
        if(empty($notes)) {
            // there is no rewrite rules matched if $notes is empty
            throw Exception\RouterException;
        }
        $path = $this->rules[$project]['bath_path'].substr($real_uri, strlen($project));
        $path = $this->normalizeUri($path);
        $class = str_replace('/', '\\', $path);
        if(class_exists($class)) {
            array_shift($notes);
            return [$class, $notes];
        }
        throw Exception\RouterException;
    }

    /**
     * return the matched rule
     *
     * @param string $uri
     * @return string
     **/
    protected function matchRule($uri) {
        foreach($this->rules as $project => $rule) {
            $preg = '#^'.$project.'#';
            if(preg_match($preg, $uri)) {
                return $project;
            }
        }
        throw Exception\RouterException;
    }

    /**
     * convent the word's first char to upper
     *
     * @examp before => '/aa/bb', after => '/Aa/Bb'
     * @return string
     **/
    protected function normalizeUri($uri) {
        $traces = [];
        foreach(explode('/', trim($uri, '/')) as $trace) {
            $traces[] = ucfirst($trace);
        }
        return sprintf('/%s', implode('/', $traces));
    }
    //TODO 设置exceptionHandle
}
