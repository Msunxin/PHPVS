<?php

namespace PHPVS;

abstract class Handler
{
    /**
     * @param Rule $rule
     * @param $data
     * @param null $args
     * @return mixed
     */
    abstract public function handle(Rule $rule, $data, $args = null);
}