<?php

namespace PHPVS\Validator;

use PHPVS\Filter;

class Preg extends Filter
{
    public $preg = null;

    public function config($preg, $_ = null, $_ = null) {
        $this->preg = $preg;
        return $this;
    }

    public function apply($data, $arg = null) {
        if (preg_match($this->preg, $data) > 0) {
            return $data;
        } else {
            return false;
        }
    }
}