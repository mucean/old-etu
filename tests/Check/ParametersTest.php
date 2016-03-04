<?php
namespace Etu\Tests\Check;

class ParametersTest extends \PHPUnit_Framework_TestCase {
    private $parameter_check = null;

    public function __construct() {
        $this->parameter_check = new \Etu\Check\Parameters();
    }

    public function testCheckOptions1() {
        $this->setExpectedException('Exception', 'must have type option');
        $this->parameter_check->check(['aa' => 'aa'], ['aa' => ['required' => true]]);
    }

    public function testCheckOptions2() {
        $this->setExpectedException('Exception', 'There have no ');
        $this->parameter_check->check(['aa' => 'aa'], ['aa' => ['type' => 'aaaa']]);
    }

    public function testCheckOptions3() {
        $this->setExpectedException('Exception', 'function or this type have no power to call');
        $this->parameter_check->check(['aa' => 'aa'], ['aa' => ['type' => 'string', 'option' => 'aaa']]);
    } 

    public function testCheckOptions4() {
        $this->setExpectedException('Exception', 'option must be an array');
        $this->parameter_check->check(['aa' => 'aa'], ['aa' => ['type' => 'array', 'option' => 'aaa']]);
    }

    public function testStringType() {
        $this->parameter_check->check(['aa' => 'aa'], ['aa' => ['type' => 'string']]);
    } 

    /**
     * 关于integer类型的测试
     *
     **/
    public function testStrCompatible() {
        $this->setExpectedException('Exception', 'it is not integer type');
        $this->parameter_check->check(['123' => []], ['123' => ['type' => 'integer']]);
    }

    public function testIntegerType() {
        $this->parameter_check->check(['123' => 123], ['123' => ['type' => 'integer']]);
        $this->parameter_check->check(['123' => '123'], ['123' => ['type' => 'integer']]);
    } 

    /**
     * 关于array类型的测试
     *
     **/
    public function testArrayType() {
        $this->parameter_check->check(
            ['aa' => ['aa' => 'aa'], 'bb' => 123],
            ['aa' => ['type' => 'array', 'option' => ['aa' => ['type' => 'string']]], 'bb' => ['type' => 'integer', 'equal' => 123]]
        );
    }

    public function testArrayNoOption() {
        $this->setExpectedException('Exception', 'array type must work with');
        $this->parameter_check->check(
            ['aa' => ['aa' => 'aa']],
            ['aa' => ['type' => 'array']]
        );
    }

    public function testHandleEmpty() {
        $this->parameter_check->check(
            ['aa' => ''],
            ['aa' => ['type' => 'string', 'allow_empty' => true]]
        );

        $this->setExpectedException('Exception', 'it is not allowed empty');
        $this->parameter_check->check(
            ['aa' => ''],
            ['aa' => ['type' => 'string', 'allow_empty' => false]]
        );
    }

    public function testHandleEqual() {
        $this->parameter_check->check(['123' => 123], ['123' => ['type' => 'integer', 'equal' => 123]]);
        $this->setExpectedException('Exception', 'it is not equal to');
        $this->parameter_check->check(['123' => 123], ['123' => ['type' => 'integer', 'equal' => 12]]);
    }

    public function testHandleRegexp() {
        $this->parameter_check->check(['123' => 123], ['123' => ['type' => 'integer', 'regexp' => '/^123$/']]);
        $this->setExpectedException('Exception', 'it is not matched');
        $this->parameter_check->check(['123' => 123], ['123' => ['type' => 'integer', 'regexp' => '/^12$/']]);
    }

    public function testHandleEnum() {
        $this->parameter_check->check(['123' => 123], ['123' => ['type' => 'integer', 'enum' => [123, 456]]]);
        $this->setExpectedException('Exception', 'the value not in enum');
        $this->parameter_check->check(['123' => 123], ['123' => ['type' => 'integer', 'enum' => [456]]]);
    }
}
