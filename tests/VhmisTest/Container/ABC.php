<?php

namespace VhmisTest\Container;

class ABC
{
    public $a;

    public $b;

    public $c = 2;

    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    public function setC($c)
    {
        $this->c = $c;
    }
}
