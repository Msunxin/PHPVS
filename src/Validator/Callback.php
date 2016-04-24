<?php

namespace PHPVS\Validator;

use PHPVS\Filter;

class Callback extends Filter
{
    public $filter = null;
    public $options = null;

    public function config($callable, $_ = null, $_ = null) {
        if (! is_callable($callable)) {
            throw new \Exception("callback is invalid.");
        }

        $this->options = $callable;
        $this->filter = FILTER_CALLBACK;
        return $this;
    }

    public function apply($data, $arg = null) {
        return filter_var($data, $this->filter, array(
            'options' => $this->options
        ));
    }
}