<?php

namespace PHPVS\Validator;

use PHPVS\Filter;

class Normal extends Filter
{
    public $filter = null;
    public $options = null;
    public $flags = null;

    public function config($filter, $options = null, $flag = null) {
        $this->filter = $filter;
        $this->flags = $flag;
        $this->options = $options;
        return $this;
    }

    public function apply($data, $arg = null) {
        $options = array(
            'options' => $this->options
        );

        if ($this->flags !== null) {
            $options['flags'] = $this->flags;
        }

        return filter_var($data, $this->filter, $options);
    }
}