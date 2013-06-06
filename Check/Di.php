<?php
error_reporting(E_ALL | E_NOTICE);

require '../Vhmis/Di/Di.php';
require '../Vhmis/Di/Service.php';

class A1
{
    protected $_b = 2;

}

class A2
{
    protected $_b = 1;
    protected $_A1Class;

    public function __construct(A1 $a)
    {
        $this->_A1Class = $a;
    }

    public function abc() {
        $this->_b = 4;
    }
}

use Vhmis\Di;

$di = new Di\Di();

$di->set('C1A', function () {
    return new A1();
});

$a = $di->get('C1A');

var_dump($a);

$di->set('C2A', '\\Vhmis\AA\\');

$b = $di->get('C2A');

var_dump($b);

$di->set('C3A', 'A1');

$a = $di->get('C3A');

var_dump($a);

$b1 = $di->get('C1A');

var_dump($b1);

var_dump($b === $b1);

$di->set('C4A', function () {
    return new A1();
}, true);

$di->set('C4A', 'A1', true);

$di->set('C9A', array(
    'class' => 'A2',
    'params' => array(
        array(
            'type' => 'service',
            'value' => 'C4A'
        )
    )
));

$b1 = $di->get('C9A');

var_dump($b1);


$di->set('AAAAAAA', array(
    'class' => 'A2',
    'params' => array(
        array(
            'type' => 'service',
            'value' => 'C4A'
        )
    ),
    'methods' => array(
        'abc' => array()
    )
));

$b1 = $di->get('AAAAAAA');

var_dump($b1);