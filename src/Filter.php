<?php

namespace PHPVS;

abstract class Filter
{
    /**
     * @param $type
     * @param null $options
     * @param null $flag
     * @return Filter
     */
    abstract function config($type, $options = null, $flag = null);

    /**
     * @param mixed $data
     * @param null $arg
     * @return bool
     */
    abstract public function apply($data, $arg = null);
}