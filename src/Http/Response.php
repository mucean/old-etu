<?php
namespace Etu\Http;

class Response {
    protected $session = [];
    protected $headers = [];
    protected $body = '';
    protected $status = 200;

    private function __construct() {
        $this->reset();
        return $this;
    }

    public static function getResponse() {
        return new static;
    }

    public function reset() {
        $this->session = [];
        $this->headers = [];
        $this->body = '';
        $this->status = 200;
    }

    public function setHeader($key, $value) {
        $this->headers[$key] = $value;
        return $this;
    }

    public function getHeader($key) {
        return isset($this->headers[$key]) ? $this->headers[$key] : false;
    }

    public function setStatus($status) {
        $this->status = (int)$status;
        return $this;
    }

    public function getStatus() {
        return $this->status ?: 200;
    }

    public function redirect($url) {
        return $this->setHeader('Location', $url);
    }

    public function setBody($body) {
        $this->body = $body;
        return $this;
    }

    public function getBody() {
        return $this->body;
    }

    public function setCookie($name, $value, $expire = 0, $path = '/', $domain = null, $secure = false, $httponly = true) {
        if($secure === false && isset($_SERVER['HTTPS'])) {
            $secure = true;
        }
        $this->cookies[$name] = [$name, $value, $expire, $path, $domain, $secure, $httponly];
        return $this;
    }

    public function send() {
        if(!headers_sent()) {
            if(($status = $this->getStatus) !== 200) {
                header(sprintf('HTTP/1.1 $d $s', $status, \Etu\Http::getStatusMessage($status)));
            }
            foreach($this->headers as $key => $value) {
                header(sprintf('$s: $s', $key, $value));
            }
            foreach($this->cookies as $cookie) {
                call_user_func_array('setcookie', $cookie);
            }
        }
        //TODO handle session
        echo $this->body;
    }
}
