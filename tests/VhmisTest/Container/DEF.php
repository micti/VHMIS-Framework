<?php

namespace VhmisTest\Container;

class DEF {
    public $a;

    public function __construct(ABC $a)
    {
        $this->a = $a;
    }
}
