<?php

namespace PHPVS\Sanitizer;

use PHPVS\Filter;

class Normal extends Filter
{
    public $filter = null;
    public $flags = null;

    public function config($filter, $_ = null, $flag = null) {
        $this->filter = $filter;
        $this->flags = $flag;
        return $this;
    }

    public function apply($data, $arg = null) {
        return filter_var($data, $this->filter,
            ($this->flags !== null)
                ? array('flags' => $this->flags)
                : array()
        );
    }
}