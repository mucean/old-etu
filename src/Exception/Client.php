<?php
namespace Etu\Exception;

class Client extends \Exception {
    protected $message = 'client exception';
    protected $code = 2000;
    protected $http_code = 500;
    protected $client_message = '';

    public function __construct($message = '', \Exception $previous = null) {
        if($message) {
            $this->client_message = strval($message);
        } else {
            if(!$this->client_message) {
                $this->client_message = $this->message;
            }
        }
        parent::__construct($this->message, $this->code, $previous);
    }

    public function getClientMessage() {
        return $this->client_message;
    }

    public function getHttpCode() {
        return $this->http_code;
    }
}
