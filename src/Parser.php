<?php

namespace PHPVS;

abstract class Parser
{
    /**
     * @param Rule $rule
     */
    abstract public function parse(Rule $rule);
}