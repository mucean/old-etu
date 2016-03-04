<?php
namespace Etu\Tests\MVC;
class RouterTest extends \PHPUnit_Framework_TestCase {
    protected $router = null;
    public function __construct() {
        $this->router = new \Etu\MVC\Router([
            '/admin' => [
                'bath_path' => '/Admin',
                'rewrite' => []
            ],
            '/etu' => [
                'bath_path' => '/etu',
                'rewrite' => [
                    '#^/etu/check/(\d+)/parameters#' => '/etu/check/parameters'
                ]
            ],
            '/' => [
                'bath_path' => '/Controller/',
                'rewrite' => [
                    '#^/user/(\d+)/follow#' => '/user/follow'
                ]
            ]
        ]);
    }

    public function testUri1() {
        $this->assertEquals(
            ['\\Etu\\Check\\Parameters', [1234]],
            $this->router->execute('/etu/check/1234/parameters')
        );
    }

    public function testUri2() {
        $this->assertEquals(
            ['\\Etu\\Check\\Parameters', []],
            $this->router->execute('/etu/check/parameters')
        );
    }

    public function testUri3() {
        $this->setExpectedException('\Exception', 'Not Found', 404);
        $this->router->execute('/aaaa');
    }
}
