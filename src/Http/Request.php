<?php
namespace Etu\Http;

class Request {
    protected $get;
    protected $post;
    protected $header;
    protected $server;
    protected $method;
    protected $cookies;
    protected $parameters = [];

    public function __construct() {
        $this->reset();
    }

    public function reset() {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->cookies = $_COOKIE;
        $this->method = null;
        $this->header = $this->headerInit();
    }
    
    public function getPost($key = null) {
        if($key === null) {
            return $this->post;
        }
        return isset($this->post[$key]) ? $this->post[$key] : false;
    }
    
    public function getGet($key = null) {
        if($key === null) {
            return $this->get;
        }
        return isset($this->get[$key]) ? $this->get[$key] : false;
    }

    public function getHeader($key = null) {
        if($key === null) {
            return $this->header;
        }
        return isset($this->header[$key]) ? $this->header[$key] : false;
    }

    protected function headerInit() {
        if($this->header) {
            return $this->header;
        }
        $headers = [];
        foreach($this->server as $key => $value) {
            $pos = strpos($key, 'HTTP_');
            if($pos === false) {
                continue;
            }
            $key = substr($key, 5);
            $headers[$key] = $value;
        }
        $this->header = $headers;
    } 

    public function getServer($key = null) {
        if($key === null) {
            return $this->server;
        }
        return isset($this->server[$key]) ? $this->server[$key] : false;
    }

    public function getMethod() {
        if($this->method) {
            return $this->method;
        }

        $method = strtoupper($this->getServer('REQUEST_METHOD'));

        if($method !== 'POST') {
            $this->method = $method;
            return $method;
        }

        if($override_method = $this->getHeader('X_HTTP_METHOD_OVERRIDE')) {
            $method = $override_method;
        }
        
        if($override_method = $this->getPost('method_override')) {
            $method = $override_method;
        }

        return $this->method = strtoupper($method);
    }

    public function getRequestURI() {
        return $this->getServer('REQUEST_URI');
    }

    public function getRequestPath() {
        return parse_url($this->getRequestURI(), PHP_URL_PATH);
    }

    public function isAjax() {
        $check = $this->getHeader('X_REQUESTED_WITH');
        return $check && strtolower($check) === 'xmlhttprequest';
    }

    public function isGet() {
        return $this->getMethod() === 'GET';
    }

    public function isPost() {
        return $this->getMethod() === 'POST';
    }

    public function isPut() {
        return $this->getMethod() === 'PUT';
    }

    public function isDelete() {
        return $this->getMethod() === 'DELETE';
    }

    public function getCookie($key = null) {
        if($key === null) {
            return $this->cookies;
        }
        return isset($this->cookies[$key]) ? $this->cookies[$key] : false;
    }
}
