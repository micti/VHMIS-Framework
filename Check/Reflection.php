<?php

namespace A\B;

class Reflection
{

    protected $_a;

    protected $_c;

    protected $_b;

    public function __construct($a, $b, $c)
    {
        $this->_a = $a;
        $this->_b = $b;
        $this->_c = $c;
    }
}

$re = new \ReflectionClass('A\B\Reflection');

$params = array(
    'a' => 1,
    'c' => 3,
    'b' => 2
);

$a = $re->newInstanceArgs($params);

var_dump($a);

$params = array(
    'a' => 1,
    'b' => 2,
    'c' => 3
);

$b = $re->newInstanceArgs($params);

var_dump($b);