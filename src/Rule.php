<?php

namespace PHPVS;

class Rule
{
    public $tips;

    protected $required;
    protected $default;
    protected $filter;
    protected $filterArgs = array();

    protected static $_filters = array();
    protected static $_parsers;
    protected static $_handlers;
    protected static $_instance;

    const VALIDATOR_NORMAL = '\\PHPVS\\Validator\\Normal';
    const VALIDATOR_CALLBACK = '\\PHPVS\\Validator\\Callback';
    const VALIDATOR_PREG = '\\PHPVS\\Validator\\Preg';
    const SANITIZER_NORMAL = '\\PHPVS\\Sanitizer\\Normal';
    const SANITIZER_CALLBACK = '\\PHPVS\\Sanitizer\\Callback';

    public function setup($filter, $args) {
        $this->required = null;
        $this->default = null;
        $this->filterArgs = $args;
        $this->filter = $filter;
        $this->tips = null;
        return $this;
    }

    public function required($bool = true) {
        $this->required = $bool;
        return $this;
    }

    public function defaulted($value) {
        $this->default = $value;
        return $this;
    }

    public function tips($value) {
        $this->tips = $value;
        return $this;
    }

    /**
     * @return Rule
     */
    public static function instance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return static::$_instance;
    }

    /**
     * @return Rule
     */
    public function filter() {
        if (!isset(self::$_filters[$this->filter])) {
            self::$_filters[$this->filter] = new $this->filter;
        }

        return self::$_filters[$this->filter];
    }

    public static function attachParser(Parser $object) {
        if (self::$_parsers === null) {
            self::$_parsers = new \SplObjectStorage();
        }

        self::$_parsers->attach($object);
    }

    public static function attachHandler(Handler $object) {
        if (self::$_handlers === null) {
            self::$_handlers = new \SplObjectStorage();
        }

        self::$_handlers->attach($object);
    }

    public static function detachHandler(Handler $object) {
        if (self::$_handlers instanceof \SplObjectStorage) {
            self::$_handlers->detach($object);
        }
    }

    /**
     * @param $tips
     * @return array
     */
    public function end($tips = null) {
        $config = array(
            'required' => $this->required,
            'default' => $this->default,
            'filter' => $this->filter,
            'filterArgs' => $this->filterArgs,
            'tips' => $tips ?: $this->tips,
        );

        if (self::$_parsers !== null) {
            foreach (self::$_parsers as $parser) {
                if ($parser instanceof Parser) {
                    $parser->parse($this);
                }
            }
        }

        return $config;
    }

    public function load($config) {
        $this->required = $config['required'];
        $this->default = $config['default'];
        $this->filter = $config['filter'];
        $this->filterArgs = $config['filterArgs'];
        $this->tips = $config['tips'];
        return $this;
    }

    /**
     * @param $data
     * @param null $args 可选参数，传递给错误处理函数
     * @param null $handler 自定义错误处理函数，如果传入false，表示忽略错误处理
     * @param Filter $injectedFilter 用于测试
     * @return mixed
     */
    public function apply($data, $args = null, $handler = null, Filter $injectedFilter = null) {
        if (strlen($data) === 0) {
            $result = false;
        } else {
            if ($injectedFilter !== null ) {
                $result = $injectedFilter->apply($data, $args);
            } else {
                $result = call_user_func_array(
                    array($this->filter(), 'config'),
                    $this->filterArgs
                )->apply($data, $args);
            }
        }

        if ($result === false || (strlen(trim($result)) === 0)) {
            if ($this->filter === self::SANITIZER_CALLBACK
                || $this->filter === self::SANITIZER_NORMAL
            ) {
                if ($this->required && strlen(trim($result)) === 0) {
                    $this->failHandle($data, $args, $handler);
                }
            } else {
                if ($this->required || strlen(trim($data)) > 0) { // input is not empty
                    $this->failHandle($data, $args, $handler);
                }
            }

            $result = $this->default;
        }

        return $result;
    }

    /**
     * apply 的封装，适用于一次性数据合法化
     *
     * @param $data
     * @param null $default
     * @return mixed
     */
    public function get($data, $default = null) {
        return $this->defaulted($default)->apply($data, null, false /* skip fail handler */);
    }

    /**
     * apply 的封装，适用于一次性数据校验
     *
     * @param $data
     * @return mixed
     */
    public function check($data) {
        return $this->required()->apply($data, null, false /* skip fail handler */);
    }

    public function failHandle($data, $args, $handler) {
        if ($handler === null) {
            if (self::$_handlers instanceof \SplObjectStorage) {
                foreach (self::$_handlers as $handler) {
                    call_user_func(array($handler, 'handle'), $this, $data, $args);
                }
            } else {
                if ($this->tips !== null) {
                    throw new \Exception($this->tips);
                } else {
                    throw new \Exception("Invalid data. [" . htmlspecialchars($data) . "]");
                }
            }
        } else {
            if ($handler === false) {
                // silently
            } else {
                call_user_func($handler, $this, $data, $args);
            }
        }
    }
}