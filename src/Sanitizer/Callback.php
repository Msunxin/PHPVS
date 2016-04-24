<?php

namespace PHPVS\Sanitizer;

use PHPVS\Filter;

class Callback extends Filter
{
    public $handler = null;

    public function config($callable, $_ = null, $_ = null) {
        if (! is_callable($callable)) {
            throw new \Exception("callback is invalid.");
        }

        $this->handler = $callable;
        return $this;
    }

    public function apply($data, $arg = null) {
        return call_user_func($this->handler, $data, $arg);
    }
}