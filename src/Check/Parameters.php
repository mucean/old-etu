<?php
namespace etu\Check;
/**
 * check parameters if you want
 *
 * @package default
 * @author MoceanLiu
 **/

class Parameters {
    /**
     * 跟踪检查路径
     *
     * @var array
     **/
    protected $trace = [];

    protected $type_check = [
        'array' => 'is_array',
        'string' => 'is_string',
        'integer' => 'literal'
    ];

    /**
     * 所有type的函数
     *
     * @var array
     **/
    protected $functions = [
        'required' => 'handleRequired',
        'allow_empty' => 'handleEmpty',
        'equal' => 'handleEqual',
        'regexp' => 'handleRegexp',
        'enum' => 'handleEnum',
        'option' => 'handleOption',
        'str_compatible' => 'handleStrCompatible',
        'allow_tags' => 'handleTags'
    ];

    /**
     * 每个type对应的function
     * 和允许用的检查函数
     * @var array
     **/
    protected $type = [
        'string' => [
            'function' => 'checkString',
            'allow_functions' => ['required', 'allow_empty', 'equal', 'enum', 'regexp', 'allow_tags']
        ],
        'integer' => [
            'function' => 'checkInteger',
            'allow_functions' => ['required', 'allow_empty', 'str_compatible', 'equal', 'enum', 'regexp']
        ],
        'array' => [
            'function' => 'checkArray',
            'allow_functions' => ['required', 'allow_empty', 'equal', 'option']
        ]
    ];

    public function check(array $data, array $options) {
        if(DEBUG) {
            $this->checkOptions($options);
        }
        foreach($options as $data_key => $option) {
            $this->trace[] = $data_key;

            $calledFunction = $this->type[$option['type']]['function'];
            unset($option['type']);
            $option = $this->addDefaultProperty($option, ['required' => true, 'allow_empty' => false]);
            $this->$calledFunction($data_key, $data, $option);
        }
        return true;
    }

    /**
     * if DEBUG is ture, will check the options if it is standard
     *
     * @return true
     * @author MoceanLiu
     **/
    protected function checkOptions(array $options) {
        foreach($options as $key => $option) {
            if(!isset($option['type'])) {
                throw new \Exception($key.' must have type option');
            }
            if(!isset($this->type[$option['type']])) {
                throw new \Exception('There have no '.$option['type'].' type');
            }
            $functions = $this->type[$option['type']]['allow_functions'];
            unset($option['type']);
            foreach($option as $op => $value) {
                if(!in_array($op, $functions)) {
                    throw new \Exception('There have no '.$op.' function or this type have no power to call');
                }
                if($op === 'option') {
                    if(!is_array($value)) {
                        throw new \Exception($key."\'s option must be an array");
                    }
                }
            }
        }
        return true;
    }

    protected function addDefaultProperty(array $option, array $default) {
        foreach($default as $property => $value) {
            if(!isset($option[$property])) {
                $option[$property] = $value;
            }
        }
        return $option;
    }

    protected function handleTags($key, $data, $option) {
        if(DEBUG) {
            if($option !== true && $option !== false) {
                throw $this->exception('allow_tags option value must be an bool value');
            }
        }
        if(!$option) {
            if (preg_match('/<\/?[a-z0-9]+(?:\s+[^>]+)?\/?>/i', $data[$key])) {
                throw $this->exception('not allow html tags');
            }
        }
    }

    protected function handleRequired($key, $data, $option) {
        if($option) {
            if(!isset($data[$key])) {
                throw $this->exception(' must be required');
            }
            return true;
        }

        if(!isset($data[$key])) {
            return false;
        }
        return true;
    }

    /**
     * if option is keyword option, call check again
     *
     * @return bool
     **/
    protected function handleOption($key, $data, $option) {
        $data = $data[$key];
        $this->check($data, $option);
        return false;
    } 
    
    /**
     * if return is false, the caller break current loop
     * else continue
     *
     * @return bool
     **/
    protected function handleEmpty($key, $data, $allow_empty) {
        $data = $data[$key];
        $allow_empty = boolval($allow_empty);
        if(!$allow_empty) {
            if(empty($data)) {
                throw $this->exception('it is not allowed empty');
            }
            return true;
        }
        if(empty($data)) {
            return false;
        }
        return true;
    }
    
    protected function handleEqual($key, $data, $equal_data) {
        $data = $data[$key];
        if($data === $equal_data) {
            return false;
        }
        throw $this->exception("it is not equal to $equal_data");
    }
    
    protected function handleRegexp($key, $data, $regexp) {
        $data = $data[$key];
        if(preg_match($regexp, strval($data))) {
            return true;
        }
        throw $this->exception('it is not matched to regexp attribute');
    }
    
    protected function handleEnum($key, $data, $enum) {
        $data = $data[$key];
        if(!is_array($enum)) {
            throw $this->exception('enum option value must be an array');
        }
        if(in_array($data, $enum)) {
            return true;
        }
        throw $this->exception('the value not in enum');
    }
    
    protected function handleType($key, $data, $type) {
        $data = $data[$key];
        if(isset($this->type[$type])) {
            $function = $this->type[$type];
            $this->$function($key, $data);
            return true;
        }
        throw $this->exception("not have $type check function");
    }

    protected function handleStrCompatible($key, &$data, $type) {
        if(is_integer($data[$key])) {
            return true;
        }

        if($data[$key] === strval(intval($data[$key]))) {
            $data[$key] = intval($data[$key]);
            return true;
        }

        throw $this->exception("it is not integer type");
    }

    protected function literal($data) {
        if(is_string($data) || is_integer($data)) {
            return true;
        }
        return false;
    }

    protected function baseCheck($data_key, $data, $options, $type = '', array $check_sequence = []) {
        if(!$this->handleRequired($data_key, $data, $options)) {
            return true;
        }
        unset($options['required']);

        if(!empty($type)) {
            if(is_callable($this->type_check[$type])) {
                if(!$this->type_check[$type]($data[$data_key])) {
                    throw $this->exception('it is not '.$type.' type');
                }
            } else {
                $function = $this->type_check[$type];
                if(!$this->$function($data[$data_key])) {
                    throw $this->exception('it is not '.$type.' type');
                }
            }
        }

        foreach($check_sequence as $op) {
            $function = $this->functions[$op];
            if($this->$function($data_key, $data, $options[$op])) {
                unset($options[$op]);
                continue;
            }
            unset($options[$op]);
            return true;
        }

        foreach($options as $op_key => $op_value) {
            $function = $this->functions[$op_key];
            if($this->$function($data_key, $data, $op_value)) {
                continue;
            }
            return true;
        }
        return true;
    }

    protected function checkArray($data_key, $data, $options) {
        if(!isset($options['option'])) {
            throw $this->exception("array type must work with \"option\" attribute");
        }
        return $this->baseCheck($data_key, $data, $options, 'array', ['allow_empty']);
    }

    protected function checkString($data_key, $data, $options) {
        $options = $this->addDefaultProperty($options, ['allow_tags' => false]);
        return $this->baseCheck($data_key, $data, $options, 'string', ['allow_empty']);
    }

    protected function checkInteger($data_key, $data, $options) {
        if(!isset($options['str_compatible'])) {
            $options['str_compatible'] = true;
        }

        return $this->baseCheck($data_key, $data, $options, 'integer', ['allow_empty', 'str_compatible']);
    }

    /**
     * format exception message
     *
     * @return \Exception
     * @author MoceanLiu
     **/
    protected function exception($message) {
        $path = 'Checked data ['.implode($this->trace, '][').']:';
        $this->trace = [];
        return new \Exception($path.$message);
    }

    //TODO 增加在DEBUG模式下对option值得检查
    //TODO 增加float type和回调函数的支持
}
