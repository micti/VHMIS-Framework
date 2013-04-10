<?php

// Cảnh báo toàn bộ
error_reporting(E_ALL | E_NOTICE);

require '../CoreVer2/Di/Di.php';

class A1 {
    protected $_b = 2;
}

class A2 {
    protected $_b = 1;
    protected $_A1Class;

    public function __construct(A1 $a)
    {
        $this->_A1Class = $a;
    }
}

use Vhmis\Di;

$di = new Di\Di();

$di->set('C1A', function() {
    return new A1;
});

$a = $di->get('C1A');

var_dump($a);

$c = $di->newInstance('A2', array('a' => $a));

var_dump($c);