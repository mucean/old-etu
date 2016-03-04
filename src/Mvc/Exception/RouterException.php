<?php
namespace Etu\Mvc\Exception;

use \Etu\Exception\Client;

class RouterException extends Client {
    protected $message = 'router exception';
    protected $http_code = 404;
    protected $client_message = 'Not Fount';
}
